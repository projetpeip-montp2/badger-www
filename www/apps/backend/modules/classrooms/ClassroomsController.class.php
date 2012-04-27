<?php
    class ClassroomsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
			$classrooms = $this->m_managers->getManagerOf('classroom')->getWithAvailabilities();
			
			$this->Page()->addVar('classrooms', $classrooms);
        }

        public function executeAddClassrooms(HTTPRequest $request)
        {
            // If the form containing the filepath exist (aka the form is
            // submitted)
            if ($request->fileExists('vbmifareClassroomsCSV'))
            {
                $fileData = $request->fileData('vbmifareClassroomsCSV');

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
                            $this->app()->user()->setFlashError('Classroom in ' . $doc->setIdPackage($idPackage) . 'csv has not got 2 rows.');
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

                    $this->app()->user()->setFlashInfo('File uploaded');
                }

                else
                    $this->app()->user()->setFlashError('Error during the upload of classrooms');
            }
        }


        public function executeAddAvailabilities(HTTPRequest $request)
        {
            $flashMessage = '';

            // Upload availabilities for a classroom
            if($request->fileExists('vbmifareAvailabilitiesCSV'))
            {
                $fileData = $request->fileData('vbmifareAvailabilitiesCSV');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $availabilities = array();

                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 3)
                        {
                            $this->app()->user()->setFlashError('Availability in csv file has not got 3 rows.');
                            $this->app()->httpResponse()->redirect($request->requestURI());
                        }

                        $date = new Date;
                        $date->setFromString($lineDatas[0]);

                        $startTime = new Time;
                        $startTime->setFromString($lineDatas[1]);

                        $endTime = new Time;
                        $endTime->setFromString($lineDatas[2]);
        
                        $availability = new Availability;
                        $availability->setIdClassroom($request->postData('vbmifareClassroom'));
                        $availability->setDate($date);
                        $availability->setStartTime($startTime);
                        $availability->setEndTime($endTime);

                        array_push($availabilities, $availability);
                    }

                    fclose($file);

                    // Save all lectures parsed
                    $this->m_managers->getManagerOf('availability')->save($availabilities);

                    $this->app()->user()->setFlashInfo('Disponibilités uploadées.');
                }

                else
                    $this->app()->user()->setFlashError('Impossible d\'uploader les disponibilités.');
            }

            // Else display the form
            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            if(count($classrooms) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins une salle pour uploader des disponibilités.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('classrooms', $classrooms);
        }

        public function executeUpdateAvailabilities(HTTPRequest $request)
        {
            // Handle POST data
            // Update availability
            if($request->postExists('Modifier'))
            {
                // Check Date and Time formats
                if(!(Date::check($request->postData('Date')) &&
                     Time::check($request->postData('StartTime')) &&
                     Time::check($request->postData('EndTime'))))
                {
                    $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/updateAvailabilities.html');
                }

                $date = new Date;
                $date->setFromString($request->postData('Date'));

                $startTime = new Time;
                $startTime->setFromString($request->postData('StartTime'));

                $endTime = new Time;
                $endTime->setFromString($request->postData('EndTime'));

                if(Time::compare($startTime, $endTime) > 0)
                {
                    $this->app()->user()->setFlashError('Horaire de début > Horaire de fin');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/updateAvailabilities.html');
                }

                $availability = new Availability();

                $availability->setId($request->postData('availabilityId'));

                $availability->setDate($date);
                $availability->setStartTime($startTime);
                $availability->setEndTime($endTime);


                $this->m_managers->getManagerOf('availability')->update($availability);

                // Redirection
                $this->app()->user()->setFlashInfo('Disponibilité de la salle ' . $request->postExists('classroomName') . ' modifiée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }

            // Delete availability
            if($request->postData('Supprimer'))
            {
                $this->m_managers->getManagerOf('availability')->delete(array($request->postData('availabilityId')));

                // Redirection
                $this->app()->user()->setFlashInfo('Disponibilité de la salle ' . $request->postData('classroomName') . ' supprimée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }

            // Else display the form
            $availabilities = $this->m_managers->getManagerOf('availability')->get();
            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            if(count($classrooms) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de salle dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }
            if(count($availabilities) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de disponibilités de salle dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }

            $this->page()->addVar('availabilities', $availabilities);
            $this->page()->addVar('classrooms', $classrooms);
        }

        public function executeUpdateClassrooms(HTTPRequest $request)
        {
            // Handle POST data
            // Update classroom
            if($request->postExists('Modifier'))
            {
                $size = $request->postData('Size');
                if(!is_int($size) && $size <= 0)
                {
                    $this->app()->user()->setFlashError('Contenance incorrecte : ' . $size);
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/updateClassrooms.html');
                }

                $classroom = new Classroom();

                $classroom->setId($request->postData('classroomId'));
                $classroom->setName($request->postData('Name'));
                $classroom->setSize($size);

                $managerClassrooms = $this->m_managers->getManagerOf('classroom');
                $managerClassrooms->update($classroom);

                // Redirection
                $this->app()->user()->setFlashInfo('Salle ' . $request->postData('Name') . ' modifiée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }

            if($request->postExists('Supprimer'))
            {
                $this->m_managers->getManagerOf('classroom')->delete(array($request->postData('classroomId')));
                $this->deleteClassroomDependancies($request->postData('classroomId'));

                // Redirection
                $this->app()->user()->setFlashInfo('Salle "' . $request->postData('Name') . '" supprimée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }

            // Else display the form
            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            if(count($classrooms) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de salles dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/classrooms/index.html');
            }

            $this->page()->addVar('classrooms', $classrooms);
        }

        private function deleteClassroomDependancies($idClassroom)
        {
            $this->m_managers->getManagerOf('availability')->deleteFromClassroom($idClassroom);
        }
    }
?>
