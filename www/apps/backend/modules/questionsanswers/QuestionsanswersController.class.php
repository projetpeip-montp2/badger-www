<?php
    class QuestionsanswersController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des questions-réponses");

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
                $this->app()->httpResponse()->redirect('/admin/home/index.html');
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
                $this->app()->user()->setFlashError('Le package demandé n\'existe pas!');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('packageIdRequested', ($packageRequested ? $packageIdRequested : $packages[0]->getId()) );
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('questions', $questions);
            $this->page()->addVar('answers', $answers);
        }

        public function executeAddQuestionsAnswers(HTTPRequest $request)
        {
            $flashMessage = '';

            // Upload questions/answers for a package
            if($request->fileExists('CSVFile'))
            {
                $fileData = $request->fileData('CSVFile');

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
                                    $this->app()->httpResponse()->redirect($request->requestURI());
                                }

                                $question = new Question;
                                $question->setIdPackage($request->postData('idPackage'));
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
                                    $this->app()->httpResponse()->redirect($request->requestURI());
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

            $this->app()->httpResponse()->redirect('/admin/questionsanswers/index.html');
        }
    }
?>
