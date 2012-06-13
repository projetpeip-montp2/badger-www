<?php
    class ConnectionController extends BackController
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

            // Check if the user is a admin
            if(!in_array($logon, explode(';', $this->m_managers->getManagerOf('config')->get('adminsList'))))
                $this->app()->httpResponse()->redirect403();

            $this->app()->user()->setAttribute('admin', true);

            $this->app()->httpResponse()->redirect('/admin/home/index.html');
        }
    }
?>
