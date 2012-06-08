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

            $currentAdminList = explode(';', $this->m_managers->getManagerOf('config')->get('adminsList'));

            if($request->postExists('Ajouter'))
            {
                $newAdmin = $request->postData('newAdmin');

                if(in_array($newAdmin, $currentAdminList))
                {
                    $this->app()->user()->setFlashInfo('Administrateur déjà présent');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                if(!empty($newAdmin))
                {
                    $currentAdminList[] = $newAdmin;
                    $newAdminList = implode(';', $currentAdminList);

                    $this->m_managers->getManagerOf('config')->replace('adminsList', $newAdminList);

                    $this->app()->user()->setFlashInfo('Nouvelle administrateur: ' . $newAdmin);
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }
            }

            if($request->postExists('deletedAdmin'))
            {
                $deleted = $request->postData('deletedAdmin');

                if(!in_array($deleted, $currentAdminList))
                {
                    $this->app()->user()->setFlashError('Cet administrateur n\'existe pas!');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                if(count($currentAdminList) == 1)
                {
                    $this->app()->user()->setFlashError('Impossible de supprimer le dernier administrateur!');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $currentAdminList = array_unique($currentAdminList);
                unset($currentAdminList[array_search($deleted, $currentAdminList)]);

                $newAdminList = implode(';', $currentAdminList);

                $this->m_managers->getManagerOf('config')->replace('adminsList', $newAdminList);

                $this->app()->user()->setFlashInfo('Administrateur retiré: ' . $deleted);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else we display the form
            $this->page()->addVar('currentAdminList', $currentAdminList);
        }

        public function executeChangeSubscribesStatus(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Statut des inscriptions");

            // Retrieve current registrations status
            $authorized = $this->m_managers->getManagerOf('config')->get('canSubscribe') != 0;

            // If the form is submitted, we replace the current registration
            // status by the new
            if($request->postExists('Interdire') || $request->postExists('Autoriser'))
            {
                $this->m_managers->getManagerOf('config')->replace('canSubscribe', $authorized ? 0 : 1);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else we display the form
            $this->page()->addVar('authorized', $authorized);
        }
    }
?>
