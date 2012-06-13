<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ConnectionController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            $manager = $this->m_managers->getManagerOf('user');
            $logon = $this->app()->user()->getAttribute('logon');

            $associatedLogon = $manager->isSpecificLogon($logon);
            if($associatedLogon !== FALSE)
            {
                $logon = $associatedLogon;
                $this->app()->user()->setAttribute('logon', $associatedLogon);
            }
            
            // If the user doesn't exist in Polytech db, he's redirected to UM2 ENT.
            $student = $manager->retrieveStudentFromPolytech($logon);
            if(!$student)
                $this->app()->httpResponse()->redirect('http://portail.univ-montp2.fr');

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

            // Absolutely don't remove it!
            $_SESSION['logDone'] = true;

            $this->app()->httpResponse()->redirect('/home/index.html');
        }
    }
?>
