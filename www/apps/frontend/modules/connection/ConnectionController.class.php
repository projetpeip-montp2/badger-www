<?php
    class ConnectionController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $manager = $this->m_managers->getManagerOf('user');
            
            // Load user's profile from the Polytech's database. If the user is 
            // on this website, the user exists obligatory in the database, 
            // because the website is on intranet
            $student = $manager->retrieveStudentFromPolytech($this->app()->user()->getAttribute('logon'));

            // Default status
            $mcqStatus = 'Visitor';

            // Check here if the visitor can take the mcq
            $managerMCQ = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQ->get();
            foreach($mcqs as $elem)
            {
                if($student->getDepartment() == $elem->getDepartment() && 
                   $student->getSchoolYear() == $elem->getSchoolYear())
                    $mcqStatus = 'CanTakeMCQ';
            }
 
            // The next function insert in database the user if it is his first
            // visit on the website. Otherwise we load his profile
            $manager->insertOrLoadIfFirstVisit($student, $mcqStatus);

            $this->app()->user()->setAttribute('vbmifareStudent', $student);

            $this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
        }
    }
?>
