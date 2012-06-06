<?php
    class SettingsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Configuration générale");
        }

        public function executeChangeAvailableAdmins(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestion des administrateurs");

            // If the form is submitted, we replace the current admin list by 
            // the new
            if($request->postExists('adminList'))
            {
                $newAdminList = $this->app()->user()->getAttribute('logon') . ';' . $request->postData('adminList');

                $this->m_managers->getManagerOf('config')->replace('adminsList', $newAdminList);

                $this->app()->user()->setFlashInfo('Nouvelle liste d\'administration: "' . $newAdminList . '".');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else we display the form
            $this->page()->addVar('admins', $this->m_managers->getManagerOf('config')->get('adminsList'));
        }

        public function executeChangeSubscribesStatus(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Statut des inscriptions");

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
