<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des conférences");

            $packages = $this->m_managers->getManagerOf('package')->get();

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
                $this->app()->httpResponse()->redirect('/admin/home/index.html');
            }

            foreach($packages as $package)
            {
                if($packageRequested && $packageIdRequested == $package->getId())
                    $found = true;
            }

            if($packageRequested && !$found)
            {
                $this->app()->user()->setFlashError('Le package demandé n\'existe pas!');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $packageIdRequested = ($packageRequested ? $packageIdRequested : $packages[0]->getId());

            $managerLectures = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLectures->get($packageIdRequested);

            $this->page()->addVar('packageIdRequested', $packageIdRequested);
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('lectures', $lectures);
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


        public function executeAddLectures(HTTPRequest $request)
        {
            $idPackage = $request->postData('idPackage');

            // Upload lectures for a package
            if($request->fileExists('CSVFile'))
            {
                $fileData = $request->fileData('CSVFile');

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
                            $this->app()->httpResponse()->redirect($request->requestURI());
                        }

                        // Check Date and Time formats
                        if(!(Date::check($lineDatas[5]) &&
                             Time::check($lineDatas[6]) &&
                             Time::check($lineDatas[7])))
                        {
                            $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire de la conférence "' . $lineDatas[0]. '".');
                            $this->app()->httpResponse()->redirect($request->requestURI());
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
                            $this->app()->httpResponse()->redirect($request->requestURI());
                        }
        
                        $lecture = new Lecture;
                        $lecture->setIdPackage($idPackage);
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

                    $managerLectures = $this->m_managers->getManagerOf('lecture');

                    // Include lectures already in this package for conflicts checking
                    $lectures = array_merge($lectures, $managerLectures->get($idPackage));

                    // Check all possible conflicts
                    for($i=0; $i<count($lectures); $i++)
                    {
                        for($j=($i+1); $j<count($lectures); $j++)
                        {
                            if(Lecture::conflict($lectures[$i], $lectures[$j]))
                            {
                                $this->app()->user()->setFlashError('Conflit entre les horaires de conférences.');
                                $this->app()->httpResponse()->redirect($request->requestURI());
                            }
                        }
                    }

                    // Save all lectures parsed
                    $managerLectures->save($lectures);

                    $this->app()->user()->setFlashInfo('Conférences uploadées.');
                }

                else
                    $this->app()->user()->setFlashError('Impossible d\'uploader les conférences.');
            }

            // Allows a redirection to the correct package
            $this->page()->addVar('idPackage', $idPackage);
        }

        public function executeAddBadgingInformation(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Ajouter des informations de badgages");

            // If form is submitted
            if($request->postExists('Envoyer'))
            {
                $username = $request->postData('username');

                $mifares = $this->m_managers->getManagerOf('user')->retrieveMifare($username);

                if(count($mifares) != 1)
                {
                    $this->app()->user()->setFlashError('Le username envoyé n\'existe pas: ' . $username . '.');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $idLecture = $request->postData('selectedLecture');
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

                $this->m_managers->getManagerOf('registration')->updateRegistrationsToPresent($username, $idLecture);
                
                $this->app()->user()->setFlashInfo('Infos de badging ajouté pour ' . $username . '.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else display the form
        }
    }
?>
