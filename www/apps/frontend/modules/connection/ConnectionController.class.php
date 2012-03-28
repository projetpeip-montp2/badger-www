<?php
    class ConnectionController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $manager = new ConnectionManager();
            
            $student = $manager->retrieveStudentFromPolytech($this->app()->user()->getAttribute('logon'));

            // TODO: Check here if the student can be on this web site

            // Ce test sert seulement pour le developpement
            if(!in_array($this->app()->user()->getAttribute('logon'), explode(';', $this->app()->configGlobal()->get('availableUsersList'))))
                $this->httpResponse->redirect403();

            $manager->insertOrLoadIfFirstVisit($student);

            $this->app()->user()->setAttribute('vbmifareStudent', $student);

            $this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
        }
    }
?>
