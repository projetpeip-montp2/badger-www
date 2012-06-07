<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des conférences");
        }


		private function validatePacket($packet)
		{
			if ($packet == null || !is_array($packet))
				return (FALSE);
			foreach ($packet as $new)
			{
  				if (!isset($new->id) || !isset($new->idAvailability) || !isset($new->idPackage))
					return (FALSE);
			}
			return (TRUE);
		}
		

		public function executeAssignLectures(HTTPRequest $request)
		{
            $this->page()->addVar("viewTitle", "Assigner des salles et disponibilités aux conférences");

			if ($request->postExists('jsonPacket'))
			{
				$this->page()->setIsAjaxPage(TRUE);
				
				$packet = json_decode($request->postData('jsonPacket'));
				if (!$this->validatePacket($packet))
					throw new Exception("Erreur JSON");
				foreach ($packet as $new)
				{
					$this->m_managers->getManagerOf('lecture')->assignAvailability($new);
				}
			}
			else
			{
				$classroomsClasses = $this->m_managers->getManagerOf('classroom')->getWithAvailabilities();
				$packagesClasses = $this->m_managers->getManagerOf('package')->get();
				$i = 0;
				
				$packages = array();
				foreach ($packagesClasses as $packageClass)
				{
					$lecturesClasses = $this->m_managers->getManagerOf('lecture')->get($packageClass->getId());
					$lectures = array();
					
					$j = 0;
					foreach ($lecturesClasses as $lectureClass)
					{
						$lectures[$j]['id'] = $lectureClass->getId();
						$lectures[$j]['idAvailability'] = $lectureClass->getIdAvailability();
						$lectures[$j]['name'] = $lectureClass->getName('fr');
						$lectures[$j]['date'] = $lectureClass->getDate()->__toString();
						$lectures[$j]['startTime'] = $lectureClass->getStartTime()->__toString();
						$lectures[$j]['endTime'] = $lectureClass->getEndTime()->__toString();
						++$j;
					}
					
					$packages[$i] = array();
					$packages[$i]['id'] = $packageClass->getId();
					$packages[$i]['name'] = $packageClass->getName('fr');
					$packages[$i]['capacity'] = $packageClass->getCapacity();
					$packages[$i]['lectures'] = $lectures;
					++$i;
				}
				
				$i = 0;
				$classrooms = array();
				foreach ($classroomsClasses as $classroomClass)
				{
					$availabilitiesClasses = $classroomClass->getAvailabilities();
					$availabilities = array();
					
					$j = 0;
					foreach ($availabilitiesClasses as $availabilityClass)
					{
						$availabilities[$j]['id'] = $availabilityClass->getId();
						$availabilities[$j]['date'] = $availabilityClass->getDate()->__toString();
						$availabilities[$j]['startTime'] = $availabilityClass->getStartTime()->__toString();
						$availabilities[$j]['endTime'] = $availabilityClass->getEndTime()->__toString();
						++$j;
					}
					
					$classrooms[$i] = array();
					$classrooms[$i]['id'] = $classroomClass->getId();
					$classrooms[$i]['name'] = $classroomClass->getName();
					$classrooms[$i]['capacity'] = $classroomClass->getSize();
					$classrooms[$i]['availabilities'] = $availabilities;
					++$i;
				}
							
				$this->page()->addVar('classrooms', $classrooms);
				$this->page()->addVar('packages', $packages);
			}
		}
		

        public function executeAddPackages(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des packages");

            // If the form containing the filepath exist (aka the form is
            // submitted)
            if ($request->fileExists('vbmifarePackagesCSV'))
            {
                $fileData = $request->fileData('vbmifarePackagesCSV');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $packages = array();

                    // Parsing package here from CSV file
                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 5)
                        {
                            $this->app()->user()->setFlashError('Le fichier n\'a pas 5 colonnes');
                            $this->app()->httpResponse()->redirect('./addPackages.html');
                            break;
                        }
        
                        $package = new Package;
                        $package->setCapacity($lineDatas[0]);
                        $package->setRegistrationsCount(0);
                        $package->setName('fr', $lineDatas[1]);
                        $package->setName('en', $lineDatas[2]);
                        $package->setDescription('fr', $lineDatas[3]);
                        $package->setDescription('en', $lineDatas[4]);

                        array_push($packages, $package);
                    }

                    fclose($file);

                    // Save all packages parsed
                    $manager = $this->m_managers->getManagerOf('package');
                    $manager->save($packages);

                    $this->app()->user()->setFlashInfo('Fichier uploadé');
                }

                else
                    $this->app()->user()->setFlashError('Erreur durant l\'upload du fichier');
            }
        }


        public function executeAddLecturesAndQuestionsAnswers(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des conférences, questions et réponses");

            // Create a flash message because we can have more than one message.
            $flashMessage = '';

            // Upload lectures for a package
            if($request->fileExists('vbmifareLecturesCSV'))
            {
                $fileData = $request->fileData('vbmifareLecturesCSV');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $lectures = array();

                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 8)
                        {
                            $this->app()->user()->setFlashError('Le fichier n\'a pas 7 colonnes');
                            $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
                        }

                        // Check Date and Time formats
                        if(!(Date::check($lineDatas[5]) &&
                             Time::check($lineDatas[6]) &&
                             Time::check($lineDatas[7])))
                        {
                            $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire de la conférence "' . $lineDatas[0]. '".');
                            $this->app()->httpResponse()->redirect('/admin/lectures/addLecturesAndQuestionsAnswers.html');
                        }

                        $date = new Date;
                        $date->setFromString($lineDatas[5]);

                        $startTime = new Time;
                        $startTime->setFromString($lineDatas[6]);

                        $endTime = new Time;
                        $endTime->setFromString($lineDatas[7]);

                        if(Time::compare($startTime, $endTime) > 0)
                        {
                            $this->app()->user()->setFlashError('Horaire de début > Horaire de fin pour la conférence ' . $lineDatas[0] . '.');
                            $this->app()->httpResponse()->redirect('/admin/lectures/addLecturesAndQuestionsAnswers.html');
                        }
        
                        $lecture = new Lecture;
                        $lecture->setIdPackage($request->postData('vbmifarePackage'));
                        $lecture->setLecturer($lineDatas[0]);
                        $lecture->setName('fr', $lineDatas[1]);
                        $lecture->setName('en', $lineDatas[2]);
                        $lecture->setDescription('fr', $lineDatas[3]);
                        $lecture->setDescription('en', $lineDatas[4]);
                        $lecture->setDate($date);
                        $lecture->setStartTime($startTime);
                        $lecture->setEndTime($endTime);

                        array_push($lectures, $lecture);
                    }

                    fclose($file);

                    // Save all lectures parsed
                    $managerLectures = $this->m_managers->getManagerOf('lecture');
                    $managerLectures->save($lectures);

                    $flashMessage = 'Conférences uploadées.';
                }

                else
                    $flashMessage = 'Impossible d\'uploader les conférences.';
            }


            // Upload questions/answers for a package
            if($request->fileExists('vbmifareQuestionsAnswersCSV'))
            {
                $fileData = $request->fileData('vbmifareQuestionsAnswersCSV');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $answers = array();
                    $readQuestion = true;
                    $lastQuestionID;

                    $managerMCQ = $this->m_managers->getManagerOf('mcq');

                    while(($line = fgets($file)) !== FALSE) 
                    {
                        if(preg_match('#__vbmifare\*#', $line))
                            $readQuestion = true;

                        else
                        {
                            $datas = str_getcsv($line);

                            if($readQuestion)
                            {
                                if(count($datas) != 3)
                                {
                                    $this->app()->user()->setFlashError('Le fichier n\'a pas 3 colonnes.');
                                    $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
                                }

                                $question = new Question;
                                $question->setIdPackage($request->postData('vbmifarePackage'));
                                $question->setLabel('fr', $datas[0]);
                                $question->setLabel('en', $datas[1]);
                                $question->setStatus($datas[2]);

                                $lastQuestionID = $managerMCQ->saveQuestion($question);

                                $readQuestion = false;
                            }

                            else
                            {
                                if(count($datas) != 3)
                                {
                                    $this->app()->user()->setFlashError('Le fichier n\'a pas 3 colonnes.');
                                    $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
                                }

                                $answer = new Answer;
                                $answer->setIdQuestion($lastQuestionID);
                                $answer->setLabel('fr', $datas[0]);
                                $answer->setLabel('en', $datas[1]);
                                $answer->setTrueOrFalse($datas[2]);

                                array_push($answers, $answer);
                            }
                        }
                    }

                    fclose($file);

                    // Save all questions/answers parsed
                    $managerMCQ->saveAnswers($answers);

                    if($flashMessage != '')
                        $flashMessage .= '<br/>';
                    $flashMessage .= 'Questions/Réponses uploadées.';
                }

                else
                    $flashMessage .= 'Impossible d\'uploader les questions/réponses.';
            }


            // Else display the form

            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader des conférences ou des questions/réponses.');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            if($flashMessage != '')
                $this->app()->user()->setFlashInfo($flashMessage);

            $this->page()->addVar('packages', $packages);
        }


        public function executeUpdatePackages(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Modifier des packages");

            // Ddisplay the form
            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de packages dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }


        public function executeUpdateLectures(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Modifier des conférences");

            // Handle POST data
            // Update lecture
            if($request->postExists('Modifier'))
            {
                // Check Date and Time formats
                if(!(Date::check($request->postData('Date')) &&
                     Time::check($request->postData('StartTime')) &&
                     Time::check($request->postData('EndTime'))))
                {
                    $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/admin/lectures/updateLectures.html');
                }

                $date = new Date;
                $date->setFromString($request->postData('Date'));

                $startTime = new Time;
                $startTime->setFromString($request->postData('StartTime'));

                $endTime = new Time;
                $endTime->setFromString($request->postData('EndTime'));

                $lecture = new Lecture();

                $lecture->setId($request->postData('lectureId'));
                $lecture->setLecturer($request->postData('Lecturer'));
                $lecture->setName('fr', $request->postData('NameFr'));
                $lecture->setName('en', $request->postData('NameEn'));
                $lecture->setDescription('fr', $request->postData('DescFr'));
                $lecture->setDescription('en', $request->postData('DescEn'));

                if(Time::compare($startTime, $endTime) > 0)
                {
                    $this->app()->user()->setFlashError('Horaire de début > Horaire de fin');
                    $this->app()->httpResponse()->redirect('/admin/lectures/updateLectures.html');
                }

                $lecture->setDate($date);
                $lecture->setStartTime($startTime);
                $lecture->setEndTime($endTime);

                $managerLectures = $this->m_managers->getManagerOf('lecture');
                $managerLectures->update($lecture);

                // Redirection
                $this->app()->user()->setFlashInfo('Conférence "' . $request->postData('NameFr') . '" modifiée.');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            // Delete lecture
            if($request->postData('Supprimer'))
            {
                $this->m_managers->getManagerOf('lecture')->delete($request->postData('lectureId'));

                // Redirection
                $this->app()->user()->setFlashInfo('Conférence "' . $request->postData('NameFr') . '" supprimée.');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            // Else display the form
            $managerLectures = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLectures->get();

            if(count($lectures) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de conférences dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            $this->page()->addVar('lectures', $lectures);
        }

        public function executeUpdateQuestionsAnswers(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Modifier des questions et réponses");

            $packages = $this->m_managers->getManagerOf('package')->get();

            $questions = array();
            $answers = array();

            $managerMCQ = $this->m_managers->getManagerOf('mcq');

            $packageRequested = false;
            if($request->postExists('packageIdRequested'))
            {
                $packageRequested = true;
                $packageIdRequested = $request->postData('packageIdRequested');
            }

            $found = false;

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Besoin d\'avoir au moins un package!');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            foreach($packages as $package)
            {
                if($packageRequested && $packageIdRequested == $package->getId())
                    $found = true;

                $questionOnePackage = $managerMCQ->getQuestionsFromPackage($package->getId());
                $questions = array_merge($questions, $questionOnePackage);

                foreach($questionOnePackage as $question)
                {
                    $answersOneQuestion = $managerMCQ->getAnswersFromQuestion($question->getId());
                    $answers = array_merge($answers, $answersOneQuestion);
                }
            }

            if($packageRequested && !$found)
            {
                $this->app()->user()->setFlashError('Le package demandé par POST n\'existe pas!');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('packageIdRequested', ($packageRequested ? $packageIdRequested : $packages[0]->getId()) );
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('questions', $questions);
            $this->page()->addVar('answers', $answers);
        }


        public function executeAddBadgingInformation(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Ajouter des informations de badgages");

            // If form is submitted
            if($request->postExists('Envoyer'))
            {
                $username = $request->postData('vbmifareUsername');

                $mifares = $this->m_managers->getManagerOf('user')->retrieveMifare($username);

                if(count($mifares) != 1)
                {
                    $this->app()->user()->setFlashError('Le username envoyé n\'existe pas: ' . $username . '.');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }


                $idLecture = $request->postData('vbmifareSelectedLecture');
                $lectures = $this->m_managers->getManagerOf('lecture')->get(-1, $idLecture);

                if(count($lectures) != 1)
                {
                    $this->app()->user()->setFlashError('L\'id de conférence envoyé ne correspond à rien!');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }


                // TODO: Vérifier que la requête à William pour mettre à jour prenne >= pour l'heure

                $this->m_managers->getManagerOf('badginginformation')->insert($mifares[0],
                                                                              $lectures[0]->getDate(),
                                                                              $lectures[0]->getStartTime());

                // TODO: Maintenant doit-on mettre à jour le status des ses inscriptions?
                // En effet, si personne ne rapelle le badger ensuite ça se fera pas tout seul..
                
                $this->app()->user()->setFlashInfo('Infos de badging ajouté pour ' . $username . '.');

                // TODO: La redirection empêche de l'affichage du flash...
                //$this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else display the form
        }

        private function deletePackageDocuments($packageId)
        {
            // Delete associated documents and images
            $path = dirname(__FILE__).'/../../../../uploads/admin/';
            
            $managerDocuments = $this->m_managers->getManagerOf('documentofpackage');
            $documents = $managerDocuments->get($packageId);

            foreach($documents as $document)
                unlink($path . 'pdf/' . $document->getFilename());

            // Delete images on server
            for($i = 1; $i <= $count; $i++)
            {
                $filename = 'images/' . $packageId . '_' . $i . '.jpg';
                unlink($path . $filename);
            }
        }

    }
?>
