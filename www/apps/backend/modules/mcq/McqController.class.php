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
                    $this->app()->user()->setFlash('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/mcq/createMCQ.html');
                }

                $date = new Date;
                $date->setFromString($request->postData('Date'));

                $startTime = new Time;
                $startTime->setFromString($request->postData('StartTime'));

                $endTime = new Time;
                $endTime->setFromString($request->postData('EndTime'));

                $mcq = new MCQ;

                $mcq->setDepartment($request->postData('Department'));
                $mcq->setSchoolYear($request->postData('SchoolYear'));
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $managerMCQs = $this->m_managers->getManagerOf('mcq');
                $managerMCQs->save($mcq);

                // Redirection
                $this->app()->user()->setFlash('Séance de QCM créée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/mcq/index.html');
            }

            // Send existing departments to the view
            $managerUsers = $this->m_managers->getManagerOf('user');
            $departments = $managerUsers->getDepartments();

            $this->page()->addVar('departments', $departments);
        }

        public function executeUpdateMCQs(HTTPRequest $request)
        {
            // Handle POST data
            if($request->postExists('Department'))
            {
            // Check Date and Time formats
                if(!(Date::check($request->postData('Date')) &&
                     Time::check($request->postData('StartTime')) &&
                     Time::check($request->postData('EndTime'))))
                {
                    $this->app()->user()->setFlash('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/mcq/updateMCQs.html');
                }

                $date = new Date;
                $date->setFromString($request->postData('Date'));

                $startTime = new Time;
                $startTime->setFromString($request->postData('StartTime'));

                $endTime = new Time;
                $endTime->setFromString($request->postData('EndTime'));

                $mcq = new MCQ;

                $mcq->setDepartment($request->postData('Department'));
                $mcq->setSchoolYear($request->postData('SchoolYear'));
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $managerMCQs = $this->m_managers->getManagerOf('mcq');
                $managerMCQs->update($mcq);

                // Redirection
                $this->app()->user()->setFlash('Modifications prises en compte.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/mcq/updateMCQs.html');
            }

            // Else display the form
            $managerMCQs = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQs->get();

            if(count($mcqs) == 0)
            {
                $this->app()->user()->setFlash('Il n\'y a pas de séances de QCMs dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/mcq/index.html');
            }

            $this->page()->addVar('mcqs', $mcqs);

            // Send existing departments to the view
            $managerUsers = $this->m_managers->getManagerOf('user');
            $departments = $managerUsers->getDepartments();

            $this->page()->addVar('departments', $departments);
        }
    }
?>
