<?php
    class SettingsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Configuration générale");
        }

        public function executeExport(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $zip = new zipfile();

            $packages = $this->m_managers->getManagerOf('package')->get();

            $lecturesManager = $this->m_managers->getManagerOf('lecture');
            $questionsManager = $this->m_managers->getManagerOf('question');
            $answersManager = $this->m_managers->getManagerOf('answer');

            $packageCSV = '';
            $lectureCSV = '';
            $questionCSV = '';

            // Generate packages.csv
            foreach($packages as $package)
            {
                $packageCSV .= '"' . $package->getCapacity() . '","' . 
                              $package->getName('fr') . '","' . 
                              $package->getName('en') . '","' . 
                              $package->getDescription('fr') . '","' . 
                              $package->getDescription('en') . '"';
                $packageCSV .= PHP_EOL;

                // Generate lectures.csv
                $lectures = $lecturesManager->get($package->getId());
                foreach($lectures as $lecture)
                {
                    $lectureCSV .= '"' . $lecture->getLecturer() . '","' . 
                                  $lecture->getName('fr') . '","' . 
                                  $lecture->getName('en') . '","' . 
                                  $lecture->getDescription('fr') . '","' . 
                                  $lecture->getDescription('en') . '","' . 
                                  $lecture->getDate() . '","' . 
                                  $lecture->getStartTime() . '","' .
                                  $lecture->getEndTime() . '"';
                    $lectureCSV .= PHP_EOL;
                }

                // Generate questions-answers.csv
                $questions = $questionsManager->get($package->getId());
                foreach($questions as $question)
                {
                    $questionCSV .= '"' . $question->getLabel('fr') . '","' . 
                                          $question->getLabel('en') . '","' . 
                                          $question->getStatus() . '"';
                    $questionCSV .= PHP_EOL;

                    $answers = $answersManager->get($question->getId());
                    foreach($answers as $answer)
                    {
                        $questionCSV .= '"' . $answer->getLabel('fr') . '","' . 
                                              $answer->getLabel('en') . '","' . 
                                              $answer->getTrueOrFalse() . '"';
                        $questionCSV .= PHP_EOL;
                    }

                    // Add question separator
                    $questionCSV .= '__vbmifare*';
                    $questionCSV .= PHP_EOL;
                }

                $zip->addfile($lectureCSV, 'packages/' . $package->getName('fr') . '/lectures.csv');
                $zip->addfile($questionCSV, 'packages/' . $package->getName('fr') . '/questions-answers.csv');
            }

            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            $availabilitesManager = $this->m_managers->getManagerOf('availability');

            $classroomCSV = '';
            $availabilityCSV = '';

            // Generate classrooms.csv
            foreach($classrooms as $classroom)
            {
                $classroomCSV .= '"' . $classroom->getName() . '","' . 
                                       $classroom->getSize() . '"';
                $classroomCSV .= PHP_EOL;

                // Generate availabilities.csv
                $availabilities = $availabilitesManager->get($classroom->getId());
                foreach($availabilities as $availability)
                {
                    $availabilityCSV .= '"' . $availability->getDate() . '","' . 
                                              $availability->getStartTime() . '","' .
                                              $availability->getEndTime() . '"';
                    $availabilityCSV .= PHP_EOL;
                }

                $zip->addfile($availabilityCSV, 'classrooms/' . $classroom->getName() . '/availabilities.csv');
            }

            $zip->addfile($packageCSV, 'packages.csv');
            $zip->addfile($classroomCSV, 'classrooms.csv');

            header('Content-Type: application/x-zip');
            header('Content-Disposition: inline; filename=exportCSV.zip');

            echo $zip->file();
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
