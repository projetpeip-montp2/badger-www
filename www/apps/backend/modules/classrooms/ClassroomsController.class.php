<?php
    class ClassroomsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestion des salles et disponilit�s");

			$classrooms = $this->m_managers->getManagerOf('classroom')->getWithAvailabilities();
			$this->Page()->addVar('classrooms', $classrooms);
        }

        public function executeAddClassrooms(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des salles");

            // If the form containing the filepath exist (aka the form is
            // submitted)
            if ($request->fileExists('CSVFile'))
            {
                $fileData = $request->fileData('CSVFile');

                // Check if the file is sucessfully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $classrooms = array();

                    // Parsing package here from CSV file
                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 2)
                        {
                            $this->app()->user()->setFlashError('Le fichier CSV n\'a pas 2 colonnes.');
                            $this->app()->httpResponse()->redirect($request->requestURI());
                            break;
                        }
        
                        $classroom = new Classroom;
                        $classroom->setName($lineDatas[0]);
                        $classroom->setSize($lineDatas[1]);

                        array_push($classrooms, $classroom);
                    }

                    fclose($file);

                    // Save all packages parsed
                    $this->m_managers->getManagerOf('classroom')->save($classrooms);

                    $this->app()->user()->setFlashInfo('Fichier upload�.');
                }

                else
                    $this->app()->user()->setFlashError('Erreur lors de l\'upload des salles.');
            }
        }


        public function executeAddAvailabilities(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des disponilit�s");

            $flashMessage = '';

            // Upload availabilities for a classroom
            if($request->fileExists('CSVFile'))
            {
                $fileData = $request->fileData('CSVFile');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $availabilities = array();

                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 3)
                        {
                            $this->app()->user()->setFlashError('Le fichier n\a pas 3 colonnes.');
                            $this->app()->httpResponse()->redirect($request->requestURI());
                        }

                        $date = new Date;
                        $date->setFromString($lineDatas[0]);

                        $startTime = new Time;
                        $startTime->setFromString($lineDatas[1]);

                        $endTime = new Time;
                        $endTime->setFromString($lineDatas[2]);
        
                        $availability = new Availability;
                        $availability->setIdClassroom($request->postData('idClassroom'));
                        $availability->setDate($date);
                        $availability->setStartTime($startTime);
                        $availability->setEndTime($endTime);

                        array_push($availabilities, $availability);
                    }

                    fclose($file);

                    // Save all lectures parsed
                    $this->m_managers->getManagerOf('availability')->save($availabilities);

                    $this->app()->user()->setFlashInfo('Disponibilit�s upload�es.');
                }

                else
                    $this->app()->user()->setFlashError('Impossible d\'uploader les disponibilit�s.');
            }

            // Else display the form
            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            if(count($classrooms) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins une salle pour uploader des disponibilit�s.');
                $this->app()->httpResponse()->redirect('/admin/classrooms/index.html');
            }

            $this->page()->addVar('classrooms', $classrooms);
        }
    }
?>
