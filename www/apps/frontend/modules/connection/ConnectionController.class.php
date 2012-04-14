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

            $mcqStatus = 'Visitor';

            // TODO: Supprimer ce test qui sert seulement pour le developpement
            ////////////////////////////////////////////////////////////////////
            if(in_array($this->app()->user()->getAttribute('logon'), explode(';', $this->app()->configGlobal()->get('availableUsersList'))))
                $mcqStatus = 'CanTakeMCQ';
            ////////////////////////////////////////////////////////////////////

            // Check here if the visitor can take the mcq
            $availableDptSchoolYear = explode(';', $this->app()->configGlobal()->get('availableStudentsList'));
            $found = false;
            foreach($availableDptSchoolYear as $elem)
            {
                $dpt = substr($elem, 0, strlen($elem)-1);
                $year = substr($elem, strlen($elem)-1);

                if($student->getDepartement() == $dpt && $student->getSchoolYear() == $year)
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
