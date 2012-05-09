<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

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
                            $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/addLecturesAndQuestionsAnswers.html');
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
                            $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/addLecturesAndQuestionsAnswers.html');
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
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            if($flashMessage != '')
                $this->app()->user()->setFlashInfo($flashMessage);

            $this->page()->addVar('packages', $packages);
        }

        public function executeUpdatePackages(HTTPRequest $request)
        {
            // Handle POST data
            // Update package
            if($request->postExists('Modifier'))
            {
                $package = new Package();

                $package->setId($request->postData('packageId'));
                $package->setCapacity($request->postData('Capacity'));
                $package->setName('fr', $request->postData('NameFr'));
                $package->setName('en', $request->postData('NameEn'));
                $package->setDescription('fr', $request->postData('DescFr'));
                $package->setDescription('en', $request->postData('DescEn'));

                $managerPackages = $this->m_managers->getManagerOf('package');
                $managerPackages->update($package);

                // Redirection
                $this->app()->user()->setFlashInfo('Package "' . $request->postData('NameFr') . '" modifié.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            // Delete lecture
            if($request->postData('Supprimer'))
            {
                $this->m_managers->getManagerOf('package')->delete(array($request->postData('packageId')));
                $this->deletePackageDependancies($request->postData('packageId'));

                // Redirection
                $this->app()->user()->setFlashInfo('Package "' . $request->postData('NameFr') . '" supprimé.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            // Else display the form
            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de packages dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }

        public function executeUpdateLectures(HTTPRequest $request)
        {
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
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/updateLectures.html');
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
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/updateLectures.html');
                }

                $lecture->setDate($date);
                $lecture->setStartTime($startTime);
                $lecture->setEndTime($endTime);

                $managerLectures = $this->m_managers->getManagerOf('lecture');
                $managerLectures->update($lecture);

                // Redirection
                $this->app()->user()->setFlashInfo('Conférence "' . $request->postData('NameFr') . '" modifiée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            // Delete lecture
            if($request->postData('Supprimer'))
            {
                $this->m_managers->getManagerOf('lecture')->delete($request->postData('lectureId'));
                $this->deleteLectureDependancies($request->postData('lectureId'));

                // Redirection
                $this->app()->user()->setFlashInfo('Conférence "' . $request->postData('NameFr') . '" supprimée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            // Else display the form
            $managerLectures = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLectures->get();

            if(count($lectures) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de conférences dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            $this->page()->addVar('lectures', $lectures);
        }

        private function deletePackageDependancies($packageId)
        {
            $lectures = $this->m_managers->getManagerOf('lecture')->get($packageId);
            foreach($lectures as $lecture)
            {
                $this->deleteLectureDependancies($lecture->getId());
                // Delete associated lectures
                $this->m_managers->getManagerOf('lecture')->delete($lecture->getId());
            }

            $questions = $this->m_managers->getManagerOf('mcq')->getQuestionsFromPackage($packageId);
            foreach($questions as $question)
                $this->deleteQuestionDependancies($question->getId());

            // Delete associated questions
             $this->m_managers->getManagerOf('mcq')->deleteQuestions($packageId);

            // Delete associated documents and images
            $path = dirname(__FILE__).'/../../../../uploads/admin/';
            
            $managerDocuments = $this->m_managers->getManagerOf('documentofpackage');
            $documents = $managerDocuments->get($packageId);

            // Delete documents database
            $managerDocuments->delete($packageId);

            foreach($documents as $document)
                unlink($path . 'pdf/' . $document->getFilename());

            $managerImages = $this->m_managers->getManagerOf('imageofpackage');
            $count = $managerImages->count($packageId);

            // Delete images database
            $managerImages->delete($packageId);

            // Delete images on server
            for($i = 1; $i <= $count; $i++)
            {
                $filename = 'images/' . $packageId . '_' . $i . '.jpg';
                unlink($path . $filename);
            }
        }

        private function deleteLectureDependancies($lectureId)
        {
            // Delete registrations to a lecture
            $this->m_managers->getManagerOf('registration')->delete($lectureId);
        }

        private function deleteQuestionDependancies($questionId)
        {
            $managerMCQ = $this->m_managers->getManagerOf('mcq');
            $managerMCQ->deleteAnswers($questionId);
            $managerMCQ->deleteQuestionsOfUsers($questionId);
            $managerMCQ->deleteAnswersOfUsers($questionId);
        }





        public function executeAddBadgingInformation(HTTPRequest $request)
        {
            if($request->postExists('Envoyer'))
            {
                $username = $request->postData('vbmifareUsername');
                $strDate = $request->postData('vbmifareDate');
                $strTime = $request->postData('vbmifareTime');

                $mifares = $this->m_managers->getManagerOf('user')->retrieveMifare($username);

                if(count($mifares) == 0)
                {
                    $this->app()->user()->setFlashError('Le username donné n\'existe pas: ' . $username . '.');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                // Check Date and Time formats
                if(! (Date::check($strDate) && Time::check($strTime)) )
                {
                    $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $date = new Date;
                $date->setFromString($strDate);

                $time = new Time;
                $time->setFromString($strTime);

                $mifare = $mifares[0];

                $this->m_managers->getManagerOf('badginginformation')->insert($mifare, $date, $time);

                // TODO: Maintenant doit-on mettre à jour le status des ses inscriptions?
                // En effet, si personne ne rapelle le badger ensuite ça se fera pas tout seul..
                
                $this->app()->user()->setFlashInfo('Infos de badging ajouté pour ' . $username . '.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
        }
    }
?>
