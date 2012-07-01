<?php
    class McqController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestion des QCM et des inscriptions");

            $managerMCQ = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQ->get();

            $this->page()->addVar('mcqs', $mcqs);
        }

        public function executeCreateMCQ(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Ajouter une inscription");

            // Handle POST data
            if($request->postExists('Envoyer'))
            {
                // Check Date and Time formats
                if(!(Date::check($request->postData('Date')) &&
                     Time::check($request->postData('StartTime')) &&
                     Time::check($request->postData('EndTime'))))
                {
                    $this->app()->user()->setFlashError('Erreur dans le format de date ou d\'horaire.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
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
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                // Real year to match the 'AnneeApogee' field in Polytech table
                $realYear = $request->postData('SchoolYear') - 2;

                if(($realYear < 1) || ($realYear > 3))
                {
                    $this->app()->user()->setFlashError('Erreur dans l\'année.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                $name = $request->postData('Name');
                if(empty($name))
                {
                    $this->app()->user()->setFlashError('Nom de QCM vide.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                $password = $request->postData('Password');
                if(empty($password))
                {
                    $this->app()->user()->setFlashError('Mot de passe vide.');
                    $this->app()->httpResponse()->redirect('/admin/mcq/createMCQ.html');
                }

                $mcq = new MCQ;

                $mcq->setDepartment($request->postData('Department'));
                $mcq->setSchoolYear($realYear);
                $mcq->setName($name);
                $mcq->setPassword($password);
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $managerMCQs = $this->m_managers->getManagerOf('mcq');

                // Include mcqs already with the same department and school year for conflicts checking
                $mcqs = array_merge(array($mcq), $managerMCQs->get($request->postData('Department'), $realYear));

                // Check all possible conflicts
                for($i=0; $i<count($mcqs); $i++)
                {
                    for($j=($i+1); $j<count($mcqs); $j++)
                    {
                        if(Tools::conflict($mcqs[$i], $mcqs[$j]))
                        {
                            $this->app()->user()->setFlashError('Conflit d\'horaires entre les QCMs déjà présents pour ce département et cette année.');
                            $this->app()->httpResponse()->redirect($request->requestURI());
                        }
                    }
                }

                $managerMCQs->save($mcq);

                $this->updateStudents($request->postData('Department'), $realYear, 'CanTakeMCQ');

                // Redirection
                $flashMessage = 'Séance de QCM pour le département ' .
                                $request->postData('Department') . ' ' .
                                $request->postData('Schoolyear') . ' créée.';
                $this->app()->user()->setFlashInfo($flashMessage);
                $this->app()->httpResponse()->redirect('/admin/mcq/index.html');
            }

            // Send existing departments to the view
            $managerUsers = $this->m_managers->getManagerOf('user');
            $departments = $managerUsers->getDepartments();

            $this->page()->addVar('departments', $departments);
        }


        private function updateStudents($department, $schoolYear, $status)
        {
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

        public function executeSendMails(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Envoi de mails");

            if($request->postExists('Envoyer'))
            {
                $students = $this->m_managers->getManagerOf('user')->get();

                foreach($students as $student)
                {
                    if($student->getMCQStatus() == 'Taken')
                    {
                        // Get questions of the students
                        $questions = $this->m_managers->getManagerOf('question')->loadQuestionsOfUser($student->getUsername());

                        // Get the associated answers
                        $answers = array();
                        foreach($questions as $question)
                            $answers = array_merge($answers, $this->m_managers->getManagerOf('answer')->get($question->getId()));

                        $answersOfUser = $this->m_managers->getManagerOf('answer')->loadAnswersOfUser($student->getUsername());

                        $mail = 'Bonjour ' . $student->getName() . ' ' . $student->getSurname() . ',<br/>
                                 Le mail ci-dessous contient vos questions et réponses au QCM de la Semaine du Numérique.<br/>';

                        $mail .= '<ul>';
                        foreach($questions as $question)
                        {
                            $answered = false;

                            $mail .= '<li>' . $question->getLabel('fr') . '</li>';
                            $mail .= '<ul>';
                            foreach($answers as $answer)
                            {
                                if($answer->getIdQuestion() == $question->getId())
                                {
                                    foreach($answersOfUser as $answerOfUser)
                                    {
                                        if($answerOfUser->getIdAnswer() == $answer->getId())
                                        {
                                            $answered = true;
                                            $mail .= '<li>' . $answer->getLabel('fr') . '</li>';
                                        }
                                    }
                                }
                            }

                            if(!$answered)
                                $mail .= '<li>Pas de réponse à cette question.</li>';
                            $mail .= '</ul>';
                        }

                        $mail .= '</ul>';

                        $mailAdress = $student->getUsername() . $this->m_managers->getManagerOf('config')->get('mailAppendix');

                        // Headers to send the mail correctly
                        $headers = 'From: ' . $this->m_managers->getManagerOf('config')->get('mailSender') . "\r\n";
                        $headers .= 'Mime-Version: 1.0'."\r\n";
                        $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
                        $headers .= "\r\n";

                        mail($mailAdress, 'Semaine du Numérique - Réponses au QCM', $mail, $headers);
                    }
                }

                $this->app()->user()->setFlashInfo('Mail envoyé aux étudiants.');
                $this->app()->httpResponse()->redirect('/admin/mcq/index.html');
            }
        }

        public function executeComputePresentMark(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Calcul de la note de présence");

            if($request->postExists('Calculer'))
            {
                $studentsManager = $this->m_managers->getManagerOf('user');

                $students = $studentsManager->get();

                foreach($students as $student)
                {
                    $presentMark = 0;

                    $managerRegistration = $this->m_managers->getManagerOf('registration');
                    $registrations = $managerRegistration->getRegistrationsFromUser($student->getUsername());

                    foreach($registrations as $reg)
                    {
                        if($reg->getStatus() == 'Present')
                            $presentMark += 20 / count($registrations);
                    }

                    $studentsManager->updatePresentMark($student->getUsername(), $presentMark);
                }

                $this->app()->user()->setFlashInfo('Calcul effectué.');
            }
        }

        public function executeComputeMCQMark(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Calcul de la note de présence");

            if($request->postExists('Calculer'))
            {
                $studentsManager = $this->m_managers->getManagerOf('user');
                $questionManager = $this->m_managers->getManagerOf('question');
                $answerManager = $this->m_managers->getManagerOf('answer');

                $students = $studentsManager->get();

                foreach($students as $student)
                {
                    $questions = $questionManager->loadQuestionsOfUser($student->getUsername());
                    $answers = $this->getAssociatedAnswers($questions);
                    $answersOfUser = $answerManager->loadAnswersOfUser($student->getUsername());

                    $QCMMark = 0;

                    // Compute good and bad answers count per question
                    $goodAndBadAnswersCount = array();
                    foreach($questions as $question)
                    {
                        $goodAnswersCount = 0;
                        $badAnswersCount = 0;

                        foreach($answers as $answer)
                        {
                            if($answer->getIdQuestion() == $question->getId())
                            {
                                if($answer->getTrueOrFalse() == 'T')
                                    $goodAnswersCount++;

                                else
                                    $badAnswersCount++;
                            }
                        }
                        
                        $goodAndBadAnswersCount[] = array($goodAnswersCount, $badAnswersCount);
                    }

                    // Add points to mark from MCQ
                    for($i=0; $i<count($questions); ++$i)
                    {
                        foreach($answersOfUser as $answerOfUser)
                        {
                            if($answerOfUser->getIdQuestion() == $questions[$i]->getId())
                            {
                                foreach($answers as $answer)
                                {
                                    if($answerOfUser->getIdAnswer() == $answer->getId())
                                    {
                                        if($answer->getTrueOrFalse() == 'T')
                                            $QCMMark += 20 / (count($questions) * $goodAndBadAnswersCount[$i][0]);
                
                                        else
                                            $QCMMark -= 20 / (count($questions) * $goodAndBadAnswersCount[$i][1]);
                                    }
                                }
                            }
                        }
                    }

                    $studentsManager->updateMCQMark($student->getUsername(), $QCMMark);
                }

                $this->app()->user()->setFlashInfo('Calcul effectué.');
            }
        }

        public function executeUpdateRegistrations(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Mise à jour des informations de présence");

            if($request->postExists('Exécuter'))
            {
                $startTime = time();

                system('php ' . dirname(__FILE__).'/../../../../scripts/updateRegistrations.php');

                $elapsedTime = time() - $startTime;

                $this->app()->user()->setFlashInfo('Mise à jour terminée en ' . $elapsedTime . ' seconde(s).');
            }
        }

        public function executeGetMarks(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $csv = '"Département","Année d\'étude","Numéro étudiant","Username","Note de présence","Note QCM","Commentaire"' . PHP_EOL;

            $managerUser = $this->m_managers->getManagerOf('user');
            $students = $managerUser->get();

            foreach($students as $student)
            {
                $status = $student->getMCQStatus();

                if($status != 'Visitor')
                {
                    $shoolYear = intval($student->getSchoolYear()) + 2;

                    $csv .= '"' . $student->getDepartment() . '","' . 
                                  $shoolYear . '","' . 
                                  $student->getStudentNumber() . '","' . 
                                  $student->getUsername() . '","' . 
                                  $student->getPresentMark() . '","' . 
                                  $student->getMCQMark() . '"';

                    $csv .= (($status == 'Taken') ? ',""' : ',"Absent"');

                    $csv .= PHP_EOL;
                }
            }

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="marks.csv"');
            echo $csv;
        }  




        private function getAssociatedAnswers($questions)
        {
            $answerManager = $this->m_managers->getManagerOf('answer');

            $answers = array();
            foreach($questions as $question)
            {
                $answersOneQuestion = $answerManager->get($question->getId());
                $answers = array_merge($answers, $answersOneQuestion);
            }

            return $answers;
        }      
    }
?>
