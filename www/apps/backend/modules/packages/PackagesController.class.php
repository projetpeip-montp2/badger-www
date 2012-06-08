<?php
    class PackagesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des conférences");
        }

        public function executeAddPackages(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des packages");

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
            $this->page()->addVar("viewTitle", "Uploader des conférences, questions et réponses");

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
                            $this->app()->httpResponse()->redirect('/admin/lectures/addLecturesAndQuestionsAnswers.html');
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
                            $this->app()->httpResponse()->redirect('/admin/lectures/addLecturesAndQuestionsAnswers.html');
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
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            if($flashMessage != '')
                $this->app()->user()->setFlashInfo($flashMessage);

            $this->page()->addVar('packages', $packages);
        }


        public function executeUpdatePackages(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Modifier des packages");

            // Ddisplay the form
            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de packages dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }

        public function executeUpdateQuestionsAnswers(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Modifier des questions et réponses");

            $packages = $this->m_managers->getManagerOf('package')->get();

            $questions = array();
            $answers = array();

            $managerMCQ = $this->m_managers->getManagerOf('mcq');

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
                $this->app()->httpResponse()->redirect('/admin/lectures/index.html');
            }

            foreach($packages as $package)
            {
                if($packageRequested && $packageIdRequested == $package->getId())
                    $found = true;

                $questionOnePackage = $managerMCQ->getQuestionsFromPackage($package->getId());
                $questions = array_merge($questions, $questionOnePackage);

                foreach($questionOnePackage as $question)
                {
                    $answersOneQuestion = $managerMCQ->getAnswersFromQuestion($question->getId());
                    $answers = array_merge($answers, $answersOneQuestion);
                }
            }

            if($packageRequested && !$found)
            {
                $this->app()->user()->setFlashError('Le package demandé par POST n\'existe pas!');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('packageIdRequested', ($packageRequested ? $packageIdRequested : $packages[0]->getId()) );
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('questions', $questions);
            $this->page()->addVar('answers', $answers);
        }

/*
        private function deletePackageDocuments($packageId)
        {
            // Delete associated documents and images
            $path = dirname(__FILE__).'/../../../../uploads/admin/';
            
            $managerDocuments = $this->m_managers->getManagerOf('documentofpackage');
            $documents = $managerDocuments->get($packageId);

            foreach($documents as $document)
                unlink($path . 'pdf/' . $document->getFilename());

            // Delete images on server
            for($i = 1; $i <= $count; $i++)
            {
                $filename = 'images/' . $packageId . '_' . $i . '.jpg';
                unlink($path . $filename);
            }
        }
*/
    }
?>
