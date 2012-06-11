<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ConnectionController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            $manager = $this->m_managers->getManagerOf('user');
            
            // If the user doesn't exist in Polytech db, he's redirected UM2 ENT.
            $student = $manager->retrieveStudentFromPolytech($this->app()->user()->getAttribute('logon'));
            if(!$student)
                $this->app()->httpResponse()->redirect('portail.univ-montp2.fr');

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

            $this->app()->httpResponse()->redirect('/home/index.html');
        }
    }
?>
