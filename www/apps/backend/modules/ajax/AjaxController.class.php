<?php
/*
    Toi qui lit ce fichier, perd tout espoir, car ton âme est déjà perdu dans les
    limbes de PHP, Javascript, et AJAX.
    En d'autres termes, si tu touches ici, t'es foutu, et tu trouveras jamais l'erreur!
    Bon courage ! :D
    G & V.
*/
    class AjaxController extends BackController
    {
		private $m_ajaxContent = '';

        private function verifyInput($ajaxInput)
        {
            $entryName = $ajaxInput->getData('entry-name');
            $fieldName = $ajaxInput->getData('field-name');
            $id = $ajaxInput->getData('id');

            $managerUser = $this->m_managers->getManagerOf('user');
            $managerPackage = $this->m_managers->getManagerOf('package');
            $managerAjax = $this->m_managers->getManagerOf('ajax');
            $managerLecture = $this->m_managers->getManagerOf('lecture');
            $managerRegistration = $this->m_managers->getManagerOf('registration');
            $managerAvailability = $this->m_managers->getManagerOf('availability');
            $managerClassroom = $this->m_managers->getManagerOf('classroom');
            $managerMCQ = $this->m_managers->getManagerOf('mcq');

	        if ($entryName == 'Packages' && $fieldName == 'Capacity')
            {
                // Check if reduced capactify is inferior than registration count
		        $managerAjax->verifyCapacity($ajaxInput);
                $newPackageCapacity = $ajaxInput->getValue();

                $classrooms = $managerClassroom->get();
                $availabilities = $managerAvailability->get();

                $flag = false;
                $lectures = $managerLecture->get($id);
                foreach($lectures as $lec)
                {
                    if($lec->getIdPackage() == $id)
                    foreach($availabilities as $avail)
                    {
                        if($lec->getIdAvailability() == $avail->getId())
                        foreach($classrooms as $class)
                        {
                            if($avail->getIdClassroom() == $class->getId())
                            {
                                if($newPackageCapacity > $class->getSize())
                                    $flag = true;
                            }
                        }
                    }
                }

                if($flag)
                foreach($lectures as $lec)
                {
                    if($lec->getIdPackage() == $id)
                        $managerLecture->unbindAvailability($lec->getId());
                }
            }

	        if ($entryName == 'Classrooms' && $fieldName == 'Size')
            {
                $newClassroomCapacity = $ajaxInput->getValue();

                $availabilities = $managerAvailability->get($id);
                $lectures = $managerLecture->get();

                $packagesDone = array();
                $lecturesToChange = array();
                foreach($lectures as $lecture)
                {
                    foreach($availabilities as $availability)
                    {
                        if($lecture->getIdAvailability() == $availability->getId())
                        {
                            $package = $managerPackage->get($lecture->getIdPackage());
                            if($newClassroomCapacity < $package[0]->getCapacity())
                                $lecturesToChange[] = $lecture->getId();
                        }
                    }
                }

                foreach($lecturesToChange as $idLec)
                    $managerLecture->unbindAvailability($idLec);
            }

            $isDateOrTime = ($fieldName == 'Date');
            $isDateOrTime |= ($fieldName == 'StartTime');
            $isDateOrTime |= ($fieldName == 'EndTime');
	        if ($entryName == 'Lectures' && $isDateOrTime)        
            {
                $subName = $ajaxInput->getData('subfield-name');

                $getter = 'get' . $fieldName;
                $setter = 'set' . $subName;

                $tmp = $managerLecture->get(-1, $id);
                $lectureToCheck = $tmp[0];
                $element = $tmp[0]->$getter();
                $element->$setter(intval($ajaxInput->getValue()));

                $users = $managerUser->get();
                $atLeastOneUserRegistered = false;
                foreach($users as $user)
                {
                    $registrations = $managerRegistration->getRegistrationsFromUser($user->getUsername());

                    $flag = false;
                    $lectures = array();
                    foreach($registrations as $reg)
                    {
                        $tmp = $managerLecture->get(-1, $reg->getIdLecture());

                        if($tmp[0]->getId() == $id)
                            $flag = $atLeastOneUserRegistered = true;
                        else
                            $lectures[] = $tmp[0];
                    }

                    if($flag)
                    {
                        $lectures[] = $lectureToCheck;
                        for($i=0; $i<count($lectures); $i++)
                        {
                            for($j=($i+1); $j<count($lectures); $j++)
                            {
                                if(Tools::conflict($lectures[$i], $lectures[$j]))
                                    throw new Exception('Conflit causé par le changement de date ou d\'heure.');
                            }
                        }
                    }
                }

                if(!$atLeastOneUserRegistered)
                {
                    $allLectures = $managerLecture->get();

                    $lectures = array();
                    foreach($allLectures as $lec)
                    {
                        if(($lec->getIdPackage() == $lectureToCheck->getIdPackage()) && ($lec->getId() != $id))
                            $lectures[] = $lec;
                    }
                    $lectures[] = $lectureToCheck;

                    for($i=0; $i<count($lectures); $i++)
                    {
                        for($j=($i+1); $j<count($lectures); $j++)
                        {
                            if(Tools::conflict($lectures[$i], $lectures[$j]))
                                throw new Exception('Conflit causé par le changement de date ou d\'heure, dans le package.');
                        }
                    }
                }

                $idAvailability = $lectureToCheck->getIdAvailability();
                if($idAvailability != 0)
                {
                    $tmp = $managerAvailability->get(-1, $idAvailability);
                    $availability = $tmp[0];

                    if(!$lectureToCheck->canUseAvailabilitiy($availability))
                        $managerLecture->unbindAvailability($lectureToCheck->getId());  
                }
            }

            $isDateOrTime = ($fieldName == 'Date');
            $isDateOrTime |= ($fieldName == 'StartTime');
            $isDateOrTime |= ($fieldName == 'EndTime');
	        if ($entryName == 'Availabilities' && $isDateOrTime)   
            {
                $subName = $ajaxInput->getData('subfield-name');

                $getter = 'get' . $fieldName;
                $setter = 'set' . $subName;

                $tmp = $managerAvailability->get(-1, $id);
                $availabilityToCheck = $tmp[0];
                $element = $tmp[0]->$getter();
                $element->$setter(intval($ajaxInput->getValue()));

                $lectures = $managerLecture->get();
                foreach($lectures as $lec)
                {
                    if($lec->getIdAvailability() == $availabilityToCheck->getId())
                    {
                        if(!$lec->canUseAvailabilitiy($availabilityToCheck))
                            $managerLecture->unbindAvailability($lec->getId());  
                    }
                }
            }

            $isDateOrTime = ($fieldName == 'Date');
            $isDateOrTime |= ($fieldName == 'StartTime');
            $isDateOrTime |= ($fieldName == 'EndTime');
	        if ($entryName == 'MCQs' && $isDateOrTime)   
            {
                $subName = $ajaxInput->getData('subfield-name');

                $getter = 'get' . $fieldName;
                $setter = 'set' . $subName;

                $allMCQs = $managerMCQ->get();
                $mcqToCheck;
                foreach($allMCQs as $mcq)
                {
                    if($mcq->getId() == $id)
                    {
                        $mcqToCheck = $mcq;
                        $element = $mcqToCheck->$getter();
                        $element->$setter(intval($ajaxInput->getValue()));
                    }
                }

                // Include mcqs already with the same department and school year for conflicts checking
                $tmp = array_merge(array($mcqToCheck), $managerMCQ->get($mcqToCheck->getDepartment(), $mcqToCheck->getSchoolYear()));

                // Remove the old mcq
                $mcqs = array($mcqToCheck);
                foreach($tmp as $t)
                {
                    if($t->getId() != $id)
                        $mcqs[] = $t;
                }

                // Check all possible conflicts
                for($i=0; $i<count($mcqs); $i++)
                {
                    for($j=($i+1); $j<count($mcqs); $j++)
                    {
                        if(Tools::conflict($mcqs[$i], $mcqs[$j]))
                            throw new Exception('Conflit causé par le changement de date ou d\'heure, dans le QCM.');
                    }
                }
            }
        }
		
        public function executeIndex(HTTPRequest $request)
        {
			$this->app()->httpResponse()->redirect('/admin/home/index.html');
        }
		
		private function postDelete($ajaxInput, $dataDeleted)
		{
			if ($ajaxInput->getData('entry-name') == 'MCQs')
				$this->updateStudents($dataDeleted['Department'], $dataDeleted['SchoolYear'], 'Visitor');

            if ($ajaxInput->getData('entry-name') == 'Availabilities')
            {
                $managerLecture = $this->m_managers->getManagerOf('lecture');

                $lectures = $managerLecture->get();
                foreach($lectures as $lec)
                {
                    if($lec->getIdAvailability() == $dataDeleted['Id_availability'])
                        $managerLecture->unbindAvailability($lec->getId());  
                }
            }
		}
		
		public function executeModifyText(HTTPRequest $request)
		{
			$allowedFields = array('Classrooms' => array('Name', 'Size'),
								   'Availabilities' => array('Date', 'StartTime', 'EndTime'),
								   'Config' => array('Value'),
								   'Packages' => array('Capacity', 'Name_fr', 'Name_en', 'Description_fr', 'Description_en'),
								   'Questions' => array('Label_fr', 'Label_en', 'Status'),
								   'Answers' => array('Label_fr', 'Label_en', 'TrueOrFalse'),
								   'Lectures' => array('Lecturer', 'Name_fr', 'Name_en', 'Description_fr', 'Description_en', 'Date', 'StartTime', 'EndTime'),
                                   'DocumentsOfPackages' => array('Filename'),
                                   'MCQs' => array('Department', 'SchoolYear', 'Name', 'Password', 'Date', 'StartTime', 'EndTime'),
                                   'ArchivesOfPackages' => array('Filename'));

			$idFields = array('Classrooms' => 'Id_classroom',
							  'Availabilities' => 'Id_availability',
							  'Questions' => 'Id_question',
							  'Answers' => 'Id_answer',
       						  'Config' => 'Name',  
							  'Packages' => 'Id_package',
							  'Lectures' => 'Id_lecture',
                              'DocumentsOfPackages' => 'Id_document',
                              'MCQs' => 'Id_mcq',
                              'ArchivesOfPackages' => 'Id_archive');

			$allowedFormType = array('text', 'number', 'textbox');
			
			$this->page()->setIsAjaxPage(TRUE);
			if($request->postExists('data-entry-name') &&
               $request->postExists('data-field-name') &&
               $request->postExists('data-form-type') &&
               $request->postExists('data-id') && $request->postExists('value') &&
               (in_array($request->postData('data-form-type'), $allowedFormType))
              )
			{
				$ajaxInput = new AjaxInput;
				$ajaxInput->setData('entry-name', $request->postData('data-entry-name'));
				$ajaxInput->setData('field-name', $request->postData('data-field-name'));
				$ajaxInput->setData('id', $request->postData('data-id'));
				$ajaxInput->setData('id-sub', $request->postExists('data-id-sub') ? $request->postData('data-id-sub') : '');
				$ajaxInput->setData('subfield-name', $request->postExists('data-subfield-name') ? $request->postData('data-subfield-name') : '');
				$ajaxInput->setData('verify-callback', $request->postExists('data-verify-callback') ? $request->postData('data-verify-callback') : '');

				$ajaxInput->setData('is-config-date', $request->postExists('is-config-date') ? $request->postData('is-config-date') : '');

				$ajaxInput->setValue($request->postData('value'));

				if (!array_key_exists($ajaxInput->getData('entry-name'), $allowedFields) || !in_array($ajaxInput->getData('field-name') , $allowedFields[$ajaxInput->getData('entry-name')]))
                    $this->addToAjaxContent('Erreur dans le formulaire.');
				else
				{
					$ajaxInput->setData('id-name', $idFields[$request->postData('data-entry-name')]);
					if ($request->postData('data-form-type') == 'number' && ($ajaxInput->getValue() == '' || !ctype_digit($ajaxInput->getValue()) || intval($ajaxInput->getValue()) < 0))
						$this->addToAjaxContent('Erreur de nombre dans le formulaire.');
					else
					{
						try
						{
							if ($ajaxInput->getData('verify-callback') == 'true')
								$this->verifyInput($ajaxInput);
						
							$this->m_managers->getManagerOf('ajax')->updateText($ajaxInput);
						}
						catch (Exception $e)
						{
							$this->addToAjaxContent('Erreur: ' . $e->getMessage());
						}
					}
				}
			}
			echo $this->getAjaxContent();
		}
		
		public function executeAddEntry(HTTPRequest $request)
		{
			$this->page()->setIsAjaxPage(TRUE);
			
			if ($request->postExists('data-entry-name') && $request->postExists('data-id'))
			{
				$ajaxInput = new AjaxInput;
				$ajaxInput->setData('entry-name', $request->postData('data-entry-name'));
				$ajaxInput->setData('id', $request->postData('data-id'));
				
				try
				{
					switch ($ajaxInput->getData('entry-name'))
					{
						case 'Classrooms':
							$this->addToAjaxContent($this->m_managers->getManagerOf('ajax')->addClassroom($ajaxInput));
							break;

						default:
							$this->addToAjaxContent('Erreur dans le formulaire.');
							break;
					}
				}
				catch (Exception $e)
				{
					$this->addToAjaxContent('Erreur d\'ajout');
				}
			}
			echo $this->getAjaxContent();
		}
		
		public function executeDeleteEntry(HTTPRequest $request)
		{
			$this->page()->setIsAjaxPage(TRUE);
			
			$allowedEntries = array('Classrooms', 
                                    'Packages',
                                    'Questions', 
                                    'Answers', 
                                    'Lectures', 
                                    'SpecificLogins',
                                    'Availabilities', 
                                    'ArchivesOfPackages', 
                                    'DocumentsOfPackages', 
                                    'MCQs', 
                                    'DocumentsOfUsers');

			$idFields = array('Classrooms' => 'Id_classroom',
                              'Questions' => 'Id_question',
                              'Answers' => 'Id_answer',
							  'Packages' => 'Id_package',
							  'Lectures' => 'Id_lecture',
							  'MCQs' => 'Id_mcq',
							  'SpecificLogins' => 'Id_login',
							  'Availabilities' => 'Id_availability',
                              'DocumentsOfPackages' => 'Id_document',
                              'ArchivesOfPackages' => 'Id_archive',
                              'DocumentsOfUsers' => 'Id_document');
			
			if ($request->postExists('data-entry-name') && $request->postExists('data-id'))
			{
				$ajaxInput = new AjaxInput;
				$ajaxInput->setData('entry-name', $request->postData('data-entry-name'));
				$ajaxInput->setData('id', $request->postData('data-id'));
				$ajaxInput->setData('post-delete', $request->postExists('post-delete') ? $request->postData('post-delete') : '');

				if (!in_array($ajaxInput->getData('entry-name'), $allowedEntries))
                    $this->addToAjaxContent('Erreur dans le formulaire.');
				else
				{
					$ajaxInput->setData('id-name', $idFields[$request->postData('data-entry-name')]);
					
					try
					{
						$dataDeleted = $this->m_managers->getManagerOf('ajax')->getObjectToDelete($ajaxInput);

						$this->m_managers->getManagerOf('ajax')->deleteEntry($ajaxInput);

						if ($ajaxInput->getData('post-delete') == 'true')
							$this->postDelete($ajaxInput, $dataDeleted);
					}
					catch (Exception $e)
					{
						$this->addToAjaxContent('Erreur de suppression');
					}
				}
			}
			echo $this->getAjaxContent();
		}
		
		public function getAjaxContent()
		{
			return ($this->m_ajaxContent);
		}
		
		public function setAjaxContent($value)
		{
			$this->m_ajaxContent = $value;
		}
		
		public function addToAjaxContent($value)
		{
			$this->m_ajaxContent .= $value;
		}

        public function executeCheckLecturesConflict(HTTPRequest $request)
        {
            $this->page()->setIsAjaxPage(TRUE);

            $username = $request->postData('username');
            $idPackage = $request->postData('idPackage');

            $packages = $this->m_managers->getManagerOf('package')->get();
            $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);

            $subscribe = true;
            foreach($registrations as $reg)
                if($reg->getIdPackage() == $idPackage)
                    $subscribe = false;

            // Registrations of the user deleted
            if(!$subscribe)
            {
                $lectures = $this->m_managers->getManagerOf('lecture')->get($idPackage);
                foreach($lectures as $lecture)
                    $this->m_managers->getManagerOf('registration')->subscribe($idPackage, $lecture->getId(), $username, 0);
            }
            // Check conflicts with other lectures and subscribe if none
            else
            {
/*
                $package = $this->m_managers->getManagerOf('package')->get($idPackage);
                $package = $package[0];
                if ($package->getRegistrationsCount() < $package->getCapacity())
                {
*/
                $lectureManager = $this->m_managers->getManagerOf('lecture');

                // Get student's lectures
                $lectures = array();
                foreach($registrations as $reg)
                    $lectures = array_merge($lectures, $lectureManager->get($reg->getIdPackage(), $reg->getIdLecture()));

                // Add new package's lectures
                $newLectures = $lectureManager->get($idPackage);

                $conflicts = array();
                foreach($newLectures as $newLecture)
                {
                    foreach($lectures as $lecture)
                    {
                        if(Tools::conflict($newLecture,$lecture) && !in_array($lecture->getIdPackage(), $conflicts))
                            $conflicts[] = $lecture->getIdPackage();
                    }
                }

                if(count($conflicts) == 0)
                    foreach($newLectures as $lecture)
                        $this->m_managers->getManagerOf('registration')->subscribe($idPackage, $lecture->getId(), $username, 1);
                else
                {
                    $packagesNames = array();
                    foreach($conflicts as $conflict)
                    {
                        foreach($packages as $package)
                        {
                            if($package->getId() == $conflict)
                                $packagesNames[] = $package->getName('fr');
                        }
                    }
                    $conflicts = (count($packagesNames) > 0) ? 'T' : 'F';
                    $result = json_encode(array('conflicts' => $conflicts, 'names' => $packagesNames));
                    if($result === FALSE)
                        throw new RuntimeException('Error during json_encode in AjaxController::checkLecturesConflicts');

        		    $this->addToAjaxContent($result);
        			echo $this->getAjaxContent();
                }
            }
        }

        public function executeAutocompleteUsername(HTTPRequest $request)
        {
            $minLenghtForAutocomplete = 1;

			$this->page()->setIsAjaxPage(TRUE);
			if($request->postExists('text'))
			{
                $input = $request->postData('text');
                $result = array();

                if( strlen($input) < $minLenghtForAutocomplete)
                {
                    $result['Found'] = 'F';
                    $result['Autocomplete'] = '';
                }    

                else
                {
                    $usernames = $this->m_managers->getManagerOf('ajax')->getUsername($input);

                    $autocomplete = '';
                    for($i = 0; $i<count($usernames); $i++)
                    {
                        $autocomplete .= $usernames[$i];

                        if($i != count($usernames)-1)
                            $autocomplete .= ';';
                    }

                    if(in_array($input, $usernames))
                    {
                        $result['Found'] = 'T';
                        $result['Lectures'] = array(); 
                        $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($input);

                        $packagesManager = $this->m_managers->getManagerOf('package');

                        $packageIds = array();
                        $packages = array();

                        foreach($registrations as $reg)
                        {
                            $lectures = $this->m_managers->getManagerOf('lecture')->get($reg->getIdPackage());

                            if(!in_array($reg->getIdPackage(), $packageIds))
                            {
                                $packageIds[] = $reg->getIdPackage();
                                $pack = $packagesManager->get($reg->getIdPackage());
                                $result['Packages'][$reg->getIdPackage()] = $pack[0]->getName('fr');
                            }

                            foreach($lectures as $lec)
                                $result['Lectures'][$lec->getId()] = array($lec->getName('fr'), $lec->getIdPackage());
                        }

                    }

                    else
                        $result['Found'] = 'F';

                    $result['Autocomplete'] = $autocomplete;

                }

                $result = json_encode($result);
                if($result === FALSE)
                    throw new RuntimeException('Error during json_encode in AjaxController::executeAutocompleteUsername');

			    $this->addToAjaxContent($result);
			}

			echo $this->getAjaxContent();
        }


        // Change student status when a mcq is remove
        private function updateStudents($department, $schoolYear, $status)
        {
            $managerMCQ = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQ->get($department, $schoolYear);

            // Change status only if there is only ONE mcq for this dpt and school year
            // after erasing one
            if( count($mcqs) > 0)
                return;

            $managerUsers = $this->m_managers->getManagerOf('user');
            $students = $managerUsers->getFromDepartmentAndSchoolYear($department, $schoolYear);

            foreach($students as $student)
            {
                $username = $student->getUsername();
                if($managerUsers->isInDatabase($username))
                {
                    $MCQStatus = $managerUsers->getMCQStatus($username);
                    if($MCQStatus == 'Visitor' || $MCQStatus == 'CanTakeMCQ')
                    {

                        // Update his status
                        $managerUsers->updateStatus($username, $status);

                        // Delete his registrations
                        $this->m_managers->getManagerOf('registration')->deleteFromUser($username);
                    }
                }
            }
        }

        
	}
?>
