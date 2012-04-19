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
                            $this->app()->user()->setFlashError('Le fichier n\'a pas 5 colonnes');
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
                        if(count($lineDatas) != 7)
                        {
                            $this->app()->user()->setFlashError('Le fichier n\'a pas 7 colonnes');
                            $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
                        }

                        // Check Date and Time formats
                        if(!(Date::check($lineDatas[4]) &&
                             Time::check($lineDatas[5]) &&
                             Time::check($lineDatas[6])))
                        {
                            $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire de la conférence "' . $lineDatas[0]. '".');
                            $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/addLecturesAndQuestionsAnswers.html');
                        }

                        $date = new Date;
                        $date->setFromString($lineDatas[4]);

                        $startTime = new Time;
                        $startTime->setFromString($lineDatas[5]);

                        $endTime = new Time;
                        $endTime->setFromString($lineDatas[6]);

                        if(Time::compare($startTime, $endTime) > 0)
                        {
                            $this->app()->user()->setFlashError('Horaire de début > Horaire de fin pour la conférence ' . $lineDatas[0] . '.');
                            $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/addLecturesAndQuestionsAnswers.html');
                        }
        
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

                    if(!$flashMessage == '')
                        $flashMessage .= '<br/>';
                    $flashMessage .= 'Questions/Réponses uploadées.';
                }

                else
                    $flashMessage .= 'Impossible d\'uploader les questions/réponses.';
            }


            // Else display the form
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moin un package pour pouvoir uploader des conférences ou des questions/réponses.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            $this->app()->user()->setFlashError($flashMessage); 

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
                $package->setLecturer($request->postData('Lecturer'));
                $package->setName('fr', $request->postData('NameFr'));
                $package->setName('en', $request->postData('NameEn'));
                $package->setDescription('fr', $request->postData('DescFr'));
                $package->setDescription('en', $request->postData('DescEn'));

                $managerPackages = $this->m_managers->getManagerOf('package');
                $managerPackages->update($package);

                // Redirection
                $this->app()->user()->setFlashInfo('Package "' . $request->postData('NameFr') . '" modifié.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/updatePackages.html');
            }

            // Delete lecture
            if($request->postData('Supprimer'))
            {
                $this->m_managers->getManagerOf('package')->delete(array($request->postData('packageId')));

                // Redirection
                $this->app()->user()->setFlashInfo('Package "' . $request->postData('NameFr') . '" supprimé.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/updatePackages.html');
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
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/updateLectures.html');
            }

            // Delete lecture
            if($request->postData('Supprimer'))
            {
                $this->m_managers->getManagerOf('lecture')->delete(array($request->postData('lectureId')));

                // Redirection
                $this->app()->user()->setFlashInfo('Conférence "' . $request->postData('NameFr') . '" supprimée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/updateLectures.html');
                // TODO: Supprimer les dépendances
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
    }
?>
