<?php
    class AjaxController extends BackController
    {
		private $m_ajaxContent = '';
		
        public function executeIndex(HTTPRequest $request)
        {
			$this->app()->httpResponse()->redirect('/admin/home/index.html');
        }
		
		public function executeModifyText(HTTPRequest $request)
		{
			$allowedFields = array('Classrooms' => array('Name', 'Size'),
								   'Availabilities' => array('Date', 'StartTime', 'EndTime'),
								   'Packages' => array('Capacity', 'Name_fr', 'Name_en', 'Description_fr', 'Description_en'),
								   'Questions' => array('Label_fr', 'Label_en', 'Status'),
								   'Answers' => array('Label_fr', 'Label_en', 'TrueOrFalse'),
                                   'ArchivesOfPackages' => array('Filename'));

			$idFields = array('Classrooms' => 'Id_classroom',
							  'Availabilities' => 'Id_availability',
							  'Questions' => 'Id_question',
							  'Answers' => 'Id_answer',
							  'Packages' => 'Id_package',
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
							$this->m_managers->getManagerOf('ajax')->updateText($ajaxInput);
						}
						catch (Exception $e)
						{
							$this->addToAjaxContent($e->getMessage());
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
							case 'Availabilities':
								$this->addToAjaxContent($this->m_managers->getManagerOf('ajax')->addAvailability($ajaxInput));
								break;
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
			
			$allowedEntries = array('Classrooms', 'Packages', 'Questions', 'Answers', 'Availabilities', 'ArchivesOfPackages');

			$idFields = array('Classrooms' => 'Id_classroom',
                              'Questions' => 'Id_question',
                              'Answers' => 'Id_answer',
							  'Packages' => 'Id_package',
							  'Availabilities' => 'Id_availability',
                              'ArchivesOfPackages' => 'Id_archive');
			
			if ($request->postExists('data-entry-name') && $request->postExists('data-id'))
			{
				$ajaxInput = new AjaxInput;
				$ajaxInput->setData('entry-name', $request->postData('data-entry-name'));
				$ajaxInput->setData('id', $request->postData('data-id'));

				if (!in_array($ajaxInput->getData('entry-name'), $allowedEntries))
                    $this->addToAjaxContent('Erreur dans le formulaire.');
				else
				{
					$ajaxInput->setData('id-name', $idFields[$request->postData('data-entry-name')]);
					
					try
					{
						$this->m_managers->getManagerOf('ajax')->deleteEntry($ajaxInput);
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

                        foreach($registrations as $reg)
                        {
                            $lectures = $this->m_managers->getManagerOf('lecture')->get($reg->getIdPackage());

                            foreach($lectures as $lec)
                                $result['Lectures'][$lec->getId()] = $lec->getName('fr');
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
	}
?>
