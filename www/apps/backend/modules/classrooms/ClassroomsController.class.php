<?php
    class ClassroomsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeAddClassrooms(HTTPRequest $request)
        {
            // If the form containing the filepath exist (aka the form is
            // submitted)
            if ($request->fileExists('vbmifareClassroomsCSV'))
            {
                $fileData = $request->fileData('vbmifareClassroomsCSV');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $classrooms = array();

                    // Parsing package here from CSV file
                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 2)
                        {
                            $this->app()->user()->setFlash('Classroom in csv has not got 2 rows');
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
                    $manager = $this->m_managers->getManagerOf('classroom');
                    $manager->save($classrooms);

                    $this->app()->user()->setFlash('File uploaded');
                }

                else
                    $this->app()->user()->setFlash('Error during the upload of classrooms');
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
                            $this->app()->user()->setFlash('Availability in csv file has not got 3 rows.');
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
                    $managerAvailabilities = $this->m_managers->getManagerOf('availability');
                    $managerAvailabilities->save($availabilities);

                    $flashMessage = 'Availabilities uploaded.';
                }

                else
                    $flashMessage = 'Cannot upload availabilities.';
            }


            // Else display the form
            $managerClassrooms = $this->m_managers->getManagerOf('classroom');
            $classrooms = $managerClassrooms->get();

            if( count($classrooms) == 0)
            {
                $this->app()->user()->setFlash('You need at least a classroom to upload availabilities');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->app()->user()->setFlash($flashMessage); 

            $this->page()->addVar('classrooms', $classrooms);
        }
    }
?>
