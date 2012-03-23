<?php
    class ConnectionController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $manager = new ConnectionManager();
            
            $student = $manager->retrieveStudent($this->app()->user()->getAttribute('logon'));

            // TODO: Check here if the student can be on the web site

            $this->app()->user()->setAttribute('vbmifareStudent', $student);

            $this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
        }
    }
?>
