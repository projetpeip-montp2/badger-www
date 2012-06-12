<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class McqController extends BackControllerFrontend
    {
        ////////////////////////////////////////////////////////////
        /// \brief Execute action Index
        ////////////////////////////////////////////////////////////
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_MCQIndex']);
            $this->page()->addVar('showMCQLink', $this->canTakeMCQ());
        }

        ////////////////////////////////////////////////////////////
        /// \brief Execute action TakeMCQ
        ////////////////////////////////////////////////////////////
        public function executeTakeMCQ(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_MCQTakeMCQ']);

            $logon = $this->app()->user()->getAttribute('logon');

            if(!$this->canTakeMCQ())
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoTakeMCQ']);
                $this->page()->addVar('showMCQLink', false);
                $this->app()->httpResponse()->redirect('/mcq/index.html');
            }

            $managerUser = $this->m_managers->getManagerOf('user');

            $mcqStatus = $this->app()->user()->getAttribute('vbmifareStudent')->getMCQStatus();
            if($mcqStatus != 'Generated')
            {
                $managerUser->updateStatus($this->app()->user()->getAttribute('logon'), 'Generated');
                $this->app()->user()->getAttribute('vbmifareStudent')->setMCQStatus('Generated');

                $questions = $this->selectQuestions();
            }
            else
                $questions = $this->loadUsersQuestions();

            // Get questions and associated answers
            $answers = $this->getAssociatedAnswers($questions);

            // If the user has validated the mcq
            // Impossible to check button because it depends of the language used
            if($request->postExists('isSubmitted'))
            {
                $answersOfUser = $this->computeAndSaveUserAnswers($request, $logon, $answers);

                // Update the user status
                $managerUser->updateStatus($logon, 'Taken');
                $this->app()->user()->getAttribute('vbmifareStudent')->setMCQStatus('Taken');

                $mark = $this->computeMark($logon, $questions, $answers, $answersOfUser);
                $managerUser->updateMark($logon, $mark);
                $this->app()->user()->getAttribute('vbmifareStudent')->setMark($mark);

                // Redirection
                $this->app()->user()->setFlashInfo($this->m_TEXT['Flash_MCQTaken']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            // Else display the form
            $this->page()->addVar('questions', $questions);
            $this->page()->addVar('answers', $answers);
            $this->page()->addVar('lang', $this->app()->user()->getAttribute('vbmifareLang'));
        }

        ////////////////////////////////////////////////////////////
        /// \brief Can Take the MCQ if correct date or not taken already
        ///
        /// \return Boolean
        ////////////////////////////////////////////////////////////
        private function canTakeMCQ()
        {
            $student = $this->app()->user()->getAttribute('vbmifareStudent');

            $department = $student->getDepartment();
            $schoolYear = $student->getSchoolYear();
            $mcqStatus = $student->getMCQStatus();

            $managerMCQ = $this->m_managers->getManagerOf('mcq');
            $mcqs = $managerMCQ->get();

            $goodDate = false;
            $goodTime = false;
            $goodDptYear = false;

            $currentDate = Date::current();
            $currentTime = Time::current();

            foreach($mcqs as $mcq)
            {
                if($department == $mcq->getDepartment() && $schoolYear == $mcq->getSchoolYear())
                    $goodDptYear = true;

                if(Date::compare($currentDate, $mcq->getDate()) == 0)
                    $goodDate = true;

                if( (Time::compare($currentTime, $mcq->getStartTime()) >= 0) &&
                    (Time::compare($currentTime, $mcq->getEndTime()) == -1) )
                    $goodTime = true;
            }

            return (in_array($mcqStatus, array('CanTakeMCQ','Generated')) && $goodDate && $goodTime && $goodDptYear);
        }

        private function selectQuestions()
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $maxQuestionNumber = $this->m_managers->getManagerOf('config')->get('MCQMaxQuestions');

            $username = $this->app()->user()->getAttribute('logon');

            $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);

            $questionManager = $this->m_managers->getManagerOf('question');

            // Get obligatory questions
            $questions = array();
            $packagesIdDo = array();
            foreach($registrations as $reg)
            {
                $idPackage = $reg->getIdPackage();

                if(! in_array($idPackage, $packagesIdDo) )
                {
                    $packagesIdDo[] = $idPackage;      

                    $questionOnePackage = $questionManager->get($idPackage, 'Obligatory');
                    $questions = array_merge($questions, $questionOnePackage);
                }
            }

            // Enough obligatory questions
            if(count($questions) > $maxQuestionNumber)
            {
                $finalQuestions = array_splice($questions, $maxQuestionNumber);
                $questionManager->saveQuestionsOfUser($this->app()->user()->getAttribute('vbmifareStudent')->getUsername(), $finalQuestions);
                return $finalQuestions;
            }

            // Count remaining questions to choose and save obligatory ones
            $remaining = $maxQuestionNumber - count($questions);
            $finalQuestions = $questions;

            // Get possible questions
            $questions = array();
            $packagesIdDo = array();
            foreach($registrations as $reg)
            {
                $idPackage = $reg->getIdPackage();

                if(! in_array($idPackage, $packagesIdDo) )
                {
                    $packagesIdDo[] = $idPackage;      

                    $questionOnePackage = $questionManager->get($idPackage, 'Possible');
                    $questions = array_merge($questions, $questionOnePackage);
                }
            }
            shuffle($questions);
            array_splice($questions, $remaining);

            $result = array_merge($finalQuestions, $questions);
            $questionManager->saveQuestionsOfUser($this->app()->user()->getAttribute('vbmifareStudent')->getUsername(), $result);
            return $result;
        }

        private function loadUsersQuestions()
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $questionManager = $this->m_managers->getManagerOf('question');

            return $questionManager->loadQuestionsOfUser($this->app()->user()->getAttribute('vbmifareStudent')->getUsername());
        }

        private function computeAndSaveUserAnswers(HTTPRequest $request, $logon, $answersInForm)
        {
            $answersOfUser = array();

            // Retrieve answers of user from the answers in the form
            foreach($answersInForm as $answer)
            {
                if($request->postExists($answer->getId()))
                {
                    $answerOfUser = new AnswerOfUser;
                    $answerOfUser->setIdUser($logon);
                    $answerOfUser->setIdQuestion($answer->getIdQuestion());
                    $answerOfUser->setIdAnswer($answer->getId());

                    array_push($answersOfUser, $answerOfUser);
                }
            }

            // Save them
            $answerManager = $this->m_managers->getManagerOf('answer');
            $answerManager->saveAnswersOfUser($answersOfUser);

            return $answersOfUser;
        }

        private function getAssociatedAnswers($questions)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $answerManager = $this->m_managers->getManagerOf('answer');

            $answers = array();
            foreach($questions as $question)
            {
                $answersOneQuestion = $answerManager->get($question->getId());
                $answers = array_merge($answers, $answersOneQuestion);
            }

            shuffle($answers);
            return $answers;
        }

        public function computeMark($logon, $questions, $answers, $answersOfUser)
        {
            $mark = 0;
    
            $presentMark = $this->m_managers->getManagerOf('config')->get('presentMark');

            $managerRegistration = $this->m_managers->getManagerOf('registration');
            $registrations = $managerRegistration->getRegistrationsFromUser($logon);

            foreach($registrations as $reg)
            {
                if($reg->getStatus() == 'Present')
                    $mark += $presentMark / count($registrations);
            }

            $QCMMark = 20 - $presentMark;

            // Compute good and bad answers count per question
            $goodAndBasAnswersCount = array();
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
                
                $goodAndBasAnswersCount[] = array($goodAnswersCount, $badAnswersCount);
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
                                    $mark += $QCMMark / (count($questions) * $goodAndBasAnswersCount[$i][0]);
        
                                else
                                    $mark -= $QCMMark / (count($questions) * $goodAndBasAnswersCount[$i][1]);
                            }
                        }
                    }
                }
            }

            return $mark;
        }
    }
?>
