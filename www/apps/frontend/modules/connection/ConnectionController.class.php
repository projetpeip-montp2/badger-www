<?php
    class ConnectionController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $manager = new ConnectionManager();
            
            $infos = $manager->retrieveInformations($this->app()->user()->getAttribute('logon'));

            if($infos == null)
                throw new RuntimeException('You are not present in Polytech user database');

            $this->app()->user()->setAttribute('infos', $infos);

            $this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
        }
    }
?>
