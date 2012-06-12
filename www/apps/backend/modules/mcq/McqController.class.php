<?php
    class McqController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestion des QCM et des inscriptions");

            $managerMCQ = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQ->get();

            $this->page()->addVar('mcqs', $mcqs);
        }

        public function executeCreateMCQ(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Ajouter une inscription");

            // Handle POST data
            if($request->postExists('Envoyer'))
            {
                // Check Date and Time formats
                if(!(Date::check($request->postData('Date')) &&
                     Time::check($request->postData('StartTime')) &&
                     Time::check($request->postData('EndTime'))))
                {
                    $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                $date = new Date;
                $date->setFromString($request->postData('Date'));

                $startTime = new Time;
                $startTime->setFromString($request->postData('StartTime'));

                $endTime = new Time;
                $endTime->setFromString($request->postData('EndTime'));

                if(Time::compare($startTime, $endTime) > 0)
                {
                    $this->app()->user()->setFlashError('Horaire de début > Horaire de fin');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                // Real year to match the 'AnneeApogee' field in Polytech table
                $realYear = $request->postData('SchoolYear') - 2;

                if(($realYear < 1) || ($realYear > 3))
                {
                    $this->app()->user()->setFlashError('Erreur dans l\'année.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                $mcq = new MCQ;

                $mcq->setDepartment($request->postData('Department'));
                $mcq->setSchoolYear($realYear);
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $managerMCQs = $this->m_managers->getManagerOf('mcq');
                $managerMCQs->save($mcq);

                $this->updateStudents($request->postData('Department'), $realYear, 'CanTakeMCQ');

                // Redirection
                $flashMessage = 'Séance de QCM pour le département ' .
                                $request->postData('Department') . ' ' .
                                $request->postData('Schoolyear') . ' créée.';
                $this->app()->user()->setFlashInfo($flashMessage);
                $this->app()->httpResponse()->redirect('/admin/mcq/index.html');
            }

            // Send existing departments to the view
            $managerUsers = $this->m_managers->getManagerOf('user');
            $departments = $managerUsers->getDepartments();

            $this->page()->addVar('departments', $departments);
        }


        private function updateStudents($department, $schoolYear, $status)
        {
            $managerUsers = $this->m_managers->getManagerOf('user');
            $students = $managerUsers->getFromDepartmentAndSchoolYear($department, $schoolYear);

            foreach($students as $student)
            {
                $username = $student->getUsername();
                if($managerUsers->isInDatabase($username))
                {
                    $MCQStatus = $managerUsers->getMCQStatus($username);
                    if($MCQStatus == 'Visitor' || $MCQStatus == 'CanTakeMCQ')
                    {

                        // Update his status
                        $managerUsers->updateStatus($username, $status);

                        // Delete his registrations
                        $this->m_managers->getManagerOf('registration')->deleteFromUser($username);
                    }
                }
            }
        }


        public function executeGetMarks(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $csv = '// "Department","SchoolYear","Username","Mark","Comment" ' . PHP_EOL;

            $managerUser = $this->m_managers->getManagerOf('user');
            $students = $managerUser->get();

            foreach($students as $student)
            {
                $status = $student->getMCQStatus();

                if($status != 'Visitor')
                {
                    $shoolYear = intval($student->getSchoolYear()) + 2;

                    $csv .= '"' . $student->getDepartment() . '","' . 
                                  $shoolYear . '","' . 
                                  $student->getStudentNumber() . '","' . 
                                  $student->getUsername() . '","' . 
                                  $student->getMark() . '"';

                    $csv .= (($status == 'Taken') ? ',""' : ',"Absent"');

                    $csv .= PHP_EOL;
                }
            }

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="marks.csv"');
            echo $csv;
        }
    }
?>
