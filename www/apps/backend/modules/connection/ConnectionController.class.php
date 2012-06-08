<?php
    class ConnectionController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            // Check if the user is a admin
            if(!in_array($this->app()->user()->getAttribute('logon'), explode(';', $this->m_managers->getManagerOf('config')->get('adminsList'))))
                $this->app()->httpResponse()->redirect403();

            $this->app()->user()->setAttribute('admin', true);

            $this->app()->httpResponse()->redirect('/admin/home/index.html');
        }
    }
?>
