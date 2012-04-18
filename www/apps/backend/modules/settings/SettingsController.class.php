<?php
    class SettingsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeChangeAvailableAdmins(HTTPRequest $request)
        {
            // If the form is submitted, we replace the current admin list by 
            // the new
            if($request->postExists('adminList'))
            {
                $newAdminList = $this->app()->user()->getAttribute('logon') . ';' . $request->postData('adminList');

                $this->m_managers->getManagerOf('config')->replace('adminsList', $newAdminList);

                $this->app()->user()->setFlash('Liste d\'Admin changée: "' . $newAdminList . '".');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else we display the form
        }

        public function executeChangeSubscribesStatus(HTTPRequest $request)
        {
            // Retrieve current registrations status
            $authorized = $this->m_managers->getManagerOf('config')->get('canSubscribe') != 0;

            // If the form is submitted, we replace the current registration
            // status by the new
            if($request->postExists('isSubmitted'))
            {
                $this->m_managers->getManagerOf('config')->replace('canSubscribe', $authorized ? 0 : 1);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else we display the form
            $this->page()->addVar('authorized', $authorized);
        }
    }
?>
