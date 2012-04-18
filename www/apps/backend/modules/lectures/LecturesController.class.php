<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

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
                            $this->app()->user()->setFlash('File has not got 5 rows');
                            $this->app()->httpResponse()->redirect('./addPackages.html');
                            break;
                        }
        
                        $package = new Package;
                        $package->setLecturer($lineDatas[0]);
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

                    $this->app()->user()->setFlash('File uploaded');
                }

                else
                    $this->app()->user()->setFlash('Error during the upload of packages');
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
                        if(count($lineDatas) != 7)
                        {
                            $this->app()->user()->setFlash('Lecture csv file has not got 7 rows.');
                            $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
                        }

                        $date = new Date;
                        $date->setFromString($lineDatas[4]);

                        $startTime = new Time;
                        $startTime->setFromString($lineDatas[5]);

                        $endTime = new Time;
                        $endTime->setFromString($lineDatas[6]);
        
                        $lecture = new Lecture;
                        $lecture->setIdPackage($request->postData('vbmifarePackage'));
                        $lecture->setName('fr', $lineDatas[0]);
                        $lecture->setName('en', $lineDatas[1]);
                        $lecture->setDescription('fr', $lineDatas[2]);
                        $lecture->setDescription('en', $lineDatas[3]);
                        $lecture->setDate($date);
                        $lecture->setStartTime($startTime);
                        $lecture->setEndTime($endTime);

                        array_push($lectures, $lecture);
                    }

                    fclose($file);

                    // Save all lectures parsed
                    $managerLectures = $this->m_managers->getManagerOf('lecture');
                    $managerLectures->save($lectures);

                    $flashMessage = 'Lectures uploaded.';
                }

                else
                    $flashMessage = 'Cannot upload lectures.';
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
                                    $this->app()->user()->setFlash('Question in csv file has not got 3 rows.');
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
                                    $this->app()->user()->setFlash('Answer in csv file has not got 3 rows.');
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

                    $flashMessage .= 'Questions/answers uploaded.';
                }

                else
                    $flashMessage .= 'Cannot upload questions/answers.';
            }


            // Else display the form
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlash('You need at least a package to upload Lectures or Questions/Answers.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            $this->app()->user()->setFlash($flashMessage); 

            $this->page()->addVar('packages', $packages);
        }

        public function executeModifyPackages(HTTPRequest $request)
        {
            // Handle POST data
            if($request->postExists('packageId'))
            {
                $package = new Package();

                $package->setId($request->postData('packageId'));
                $package->setLecturer($request->postData('Lecturer'));
                $package->setNameFr($request->postData('NameFr'));
                $package->setNameEn($request->postData('NameEn'));
                $package->setDescriptionFr($request->postData('DescFr'));
                $package->setDescriptionEn($request->postData('DescEn'));

                $managerPackages = $this->m_managers->getManagerOf('package');
                $managerPackages->saveModifications($package);

                // Redirection
                $this->app()->user()->setFlash('Modifications prises en compte');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/modifyPackages.html');
            }

            // Else display the form
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get($lang);

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlash('Il n\'y a pas de packages dans la base de données.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }
    }
?>
