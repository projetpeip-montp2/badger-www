<?php
    class McqController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeCreateMCQ(HTTPRequest $request)
        {
            // Handle POST data
            if($request->postExists('isSubmitted'))
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

                $mcq = new MCQ;

                $mcq->setDepartment($request->postData('Department'));
                $mcq->setSchoolYear($request->postData('SchoolYear'));
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $managerMCQs = $this->m_managers->getManagerOf('mcq');
                $managerMCQs->save($mcq);

                $mcqId = array('Department' => $request->postData('Department'), 'SchoolYear' =>$request->postData('SchoolYear'));

                $this->updateStudents($mcqId, 'CanTakeMCQ');

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

        public function executeUpdateMCQs(HTTPRequest $request)
        {
            // Handle POST data
            // Update MCQ
            if($request->postExists('Modifier'))
            {
            // Check Date and Time formats
                if(!(Date::check($request->postData('Date')) &&
                     Time::check($request->postData('StartTime')) &&
                     Time::check($request->postData('EndTime'))))
                {
                    $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/updateMCQs.html');
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
                    $this->app()->httpResponse()->redirect('/admin/mcq/updateMCQs.html');
                }

                $mcq = new MCQ;

                $mcq->setDepartment($request->postData('Department'));
                $mcq->setSchoolYear($request->postData('SchoolYear'));
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $managerMCQs = $this->m_managers->getManagerOf('mcq');
                $managerMCQs->update($mcq);

                // Redirection
                $flashMessage = 'Séance de QCM pour le département ' .
                                $request->postData('Department') . ' ' .
                                $request->postData('Schoolyear') . ' modifiée.';
                $this->app()->user()->setFlashInfo($flashMessage);
                $this->app()->httpResponse()->redirect('/admin/mcq/index.html');
            }

            // Delete MCQ
            if($request->postExists('Supprimer'))
            {
                // MCQ identificated by Department & Schoolyear ==> Send an array containing both information to manager
                $mcqId = array('Department' => $request->postData('Department'), 'SchoolYear' =>$request->postData('SchoolYear'));

                $this->updateStudents($mcqId, 'Visitor');
                $this->m_managers->getManagerOf('mcq')->delete(array($mcqId));

                // Redirection
                $flashMessage = 'Séance de QCM pour le département ' .
                                $request->postData('Department') . ' ' .
                                $request->postData('Schoolyear') . ' supprimée.';
                $this->app()->user()->setFlashInfo($flashMessage);
                $this->app()->httpResponse()->redirect('/admin/mcq/index.html');
            }

            // Else display the form
            $managerMCQs = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQs->get();

            if(count($mcqs) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de séances de QCMs dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/mcq/index.html');
            }

            $this->page()->addVar('mcqs', $mcqs);

            // Send existing departments to the view
            $managerUsers = $this->m_managers->getManagerOf('user');
            $departments = $managerUsers->getDepartments();

            $this->page()->addVar('departments', $departments);
        }

        private function updateStudents($mcqId, $status)
        {
            $managerUsers = $this->m_managers->getManagerOf('user');
            $students = $managerUsers->getFromDepartmentAndSchoolYear($mcqId['Department'], $mcqId['SchoolYear']);

            foreach($students as $student)
            {
                $username = $student->getUsername();
                if($managerUsers->isInDatabase($username))
                {
                    // Update his status
                    $managerUsers->updateStatus($username, $status);

                    // Delete his registrations
                    $this->m_managers->getManagerOf('registration')->deleteFromUser($username);
                }
            }
        }


        public function executeGetMarks(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $csv = '// "Department","SchoolYear","Username","Mark","Comment" ' . PHP_EOL;

            $managerUser = $this->m_managers->getManagerOf('user');
            $students = $managerUser->getAllFromvbMifare();

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

            $this->page()->addVar('csv', $csv);
        }
    }
?>
