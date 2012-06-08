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
    }
?>
