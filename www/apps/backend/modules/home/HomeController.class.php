<?php
    class HomeController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Accueil administration');
        }

        public function executeEdit(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Edition');
        }

        public function executeShowInfos(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Informations");

            $packagesManager = $this->m_managers->getManagerOf('package');
            $lecturesManager = $this->m_managers->getManagerOf('lecture');
            $questionsManager = $this->m_managers->getManagerOf('question');
            $answersManager = $this->m_managers->getManagerOf('answer');
            $usersManager = $this->m_managers->getManagerOf('user');
            $configManager = $this->m_managers->getManagerOf('config');

            $packages = $packagesManager->get();
            $questions = $questionsManager->get();
            $users = $usersManager->get();
            
            $noLecturePackages = array();
            $notEnoughRegPackages = array();
            foreach($packages as $package)
            {
                // Detect packages with no lecture 
                if(count($lecturesManager->get($package->getId())) == 0)
                    $noLecturePackages[] = $package;

                // Detect packages with not enough registrations
                if($package->getRegistrationsCount() < $configManager->get('minRegistrationsPerPackage'))
                $notEnoughRegPackages[] = $package;
            }

            $noAnswerQuestions = array();
            foreach($questions as $question)
            {
                // Detect questions with no answer
                if(count($answersManager->get($question->getId())) == 0)
                    $noAnswerQuestions[] = $question;
            }

            $incompleteStudents = array();
            foreach($users as $user)
            {
                $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($user->getUsername());
                $count = $this->countSelectedPackages($registrations);

                // Detect students with not enough registrations
                if($count < $configManager->get('packageRegistrationsCount'))
                    $incompleteStudents[] = $user;
            }

            $this->page()->addVar('allPackages', $packages);
            $this->page()->addVar('noLecturePackages', $noLecturePackages);
            $this->page()->addVar('notEnoughRegPackages', $notEnoughRegPackages);
            $this->page()->addVar('noAnswerQuestions', $noAnswerQuestions);
            $this->page()->addVar('incompleteStudents', $incompleteStudents);
        }

        public function executeLogout(HTTPRequest $request)
        {
            phpCAS::logout();
        }

        private function countSelectedPackages($registrations)
        {
            $existingPackages = array();
            foreach($registrations as $reg)
            {
                if(!in_array($reg->getIdPackage(), $existingPackages))
                    $existingPackages[] = $reg->getIdPackage();
            }

            return count($existingPackages);
        }
    }
?>
