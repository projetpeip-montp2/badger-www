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
                if(!(Date::checkDate($request->postData('Date')) ||
                     Time::checkTime($request->postData('StartTime')) ||
                     Time::checkTime($request->postData('StartTime'))))
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
        }
    }
?>
