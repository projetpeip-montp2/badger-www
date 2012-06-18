<?php
    class SettingsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Configuration générale");

            // Warning: be sure before you change this array!
            $configNames = array('MCQMaxQuestions' => 'number',
                                 'packageRegistrationsCount' => 'number',
                                 'minRegistrationsPerPackage' => 'number',
                                 'mailAppendix' => 'textbox',
                                 'mailSender' => 'textbox',
                                 'canSubscribe' => 'binary',
                                 'canViewPlanning' => 'binary',
                                 'canHandleReports' => 'binary',
                                 'registrationsDateLimit' => 'date',
                                 'reportSizeLimitFrontend' => 'number',
                                 'documentSizeLimitBackend' => 'number',
                                 'zipFileSizeLimitBackend' => 'number');
            $configDescriptions = array();
            $configValues = array();

            $configManager = $this->m_managers->getManagerOf('config');

            foreach($configNames as $name => $type)
            {
                $configValues[$name] = $configManager->get($name);
                $configDescriptions[$name] = $configManager->getDescription($name);
            }

            $this->page()->addVar('configNames', $configNames);
            $this->page()->addVar('configDescriptions', $configDescriptions);
            $this->page()->addVar('configValues', $configValues);
        }

        public function executeReplicate(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Réplication de la base de données Polytech'");

            if($request->postExists('Exécuter'))
            {
                $startTime = time();

                system('php ' . dirname(__FILE__).'/../../../../scripts/replicate.php');
                system('php ' . dirname(__FILE__).'/../../../../scripts/updateRegistrations.php');

                $elapsedTime = time() - $startTime;

                $this->app()->user()->setFlashInfo('Réplication terminée en ' . $elapsedTime . ' seconde(s).');
            }
        }

        public function executeUpdateRegistrations(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Mise à jour des informations de présence");

            if($request->postExists('Exécuter'))
            {
                $startTime = time();

                system('php ' . dirname(__FILE__).'/../../../../scripts/updateRegistrations.php');

                $elapsedTime = time() - $startTime;

                $this->app()->user()->setFlashInfo('Mise à jour terminée en ' . $elapsedTime . ' seconde(s).');
            }
        }

        public function executeChangeSpecificLogins(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestion des logins spécifiques");

            $userManager = $this->m_managers->getManagerOf('user');
            $specificLogins = $userManager->getSpecificLogins('adminsList');

            if($request->postExists('Ajouter'))
            {
                $um2 = $request->postData('loginUM2');
                $poly = $request->postData('loginPoly');

                if( empty($um2) || empty($poly) )
                {
                    $this->app()->user()->setFlashError('Un des deux champs est vide!');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $userManager->insertSpecificLogins($um2, $poly);

                $this->app()->user()->setFlashInfo('Nouveau login spécifique ajouté: ' . $um2 . ' => ' . $poly . '.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('specificLogins', $specificLogins);
        }

        public function executeComputePresentMark(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Calcul de la note de présence");

            if($request->postExists('Calculer'))
            {
                $studentsManager = $this->m_managers->getManagerOf('user');

                $students = $studentsManager->get();

                foreach($students as $student)
                {
                    $presentMark = 0;

                    $managerRegistration = $this->m_managers->getManagerOf('registration');
                    $registrations = $managerRegistration->getRegistrationsFromUser($student->getUsername());

                    foreach($registrations as $reg)
                    {
                        if($reg->getStatus() == 'Present')
                            $presentMark += 20 / count($registrations);
                    }

                    $studentsManager->updatePresentMark($student->getUsername(), $presentMark);
                }
            }
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
    }
?>
