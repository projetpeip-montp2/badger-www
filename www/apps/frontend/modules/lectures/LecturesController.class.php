<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeShow(HTTPRequest $request)
        {
            $username = $this->app()->user()->getAttribute('logon');

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            // Retrieve registration id of the users
            $managerRegistration = $this->m_managers->getManagerOf('registration');
            $registrationsOfUser = $managerRegistration->getResgistrationsFromUser($username);

            // Retrieve the package given by id in URL
            $managerPackage = $this->m_managers->getManagerOf('package');
            $packages = $managerPackage->get($lang, $request->getData('idPackage'));

            // Check that the package exists
            if(count($packages) != 1)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $this->app()->user()->setFlash($TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/vbMifare/lectures/showAll.html');
            }

            $package = $packages[0];

            $wantSubscribe = true;
            foreach($registrationsOfUser as $reg)
            {
                if($reg->getIdPackage() == $package->getId() )
                    $wantSubscribe = false;
            }

            // If the form is submitted, do the registration
            if($request->postExists('isSubmitted'))
            {
                $this->checkSubscribe($request);

                if($wantSubscribe)
                    $this->checkConflict($lang, $request, $registrationsOfUser, $package);

                $managerRegistration->subscribe($request->getData('idPackage'), $username, $wantSubscribe ? 1 : 0);

                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $this->app()->user()->setFlash($wantSubscribe ? $TEXT['Flash_SubscribeOk'] : $TEXT['Flash_UnsubscribeOk']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else display the form
            $this->page()->addVar('wantSubscribe', $wantSubscribe);

            $this->page()->addVar('package', $package);
            $this->page()->addVar('lang', $lang);

            $managerLecture = $this->m_managers->getManagerOf('lecture');
            $this->page()->addVar('lectures', $managerLecture->get($lang, $request->getData('idPackage')));
        }

        public function executeShowAll(HTTPRequest $request)
        {
            // Display all packages
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackage = $this->m_managers->getManagerOf('package');

            $this->page()->addVar('packages', $managerPackage->get($lang, -1));
            $this->page()->addVar('lang', $lang);
        }

        public function executeSchedule()
        {
            // Display schedule
        }








        private function checkSubscribe(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');
            require dirname(__FILE__).'/../../lang/' . $lang . '.php';

            $status = $this->app()->user()->getAttribute('vbmifareStudent')->getMCQStatus();

            // Check is user's status is allowed for registrations
            if($status != 'CanTakeMCQ')
            {
                $flashMessage = '';

                switch($status)
                {
                case 'Visitor':
                    $flashMessage = $TEXT['Flash_SubscribeVisitor'];
                    break;

                case 'Generated':
                    $flashMessage = $TEXT['Flash_SubscribeGenerated'];
                    break;

                case 'Taken':
                    $flashMessage = $TEXT['Flash_SubscribeTaken'];
                    break;

                default:
                    $flashMessage = 'Your status is unknow :)';
                    break;
                }

                $this->app()->user()->setFlash($flashMessage);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }


            // Check if registrations are allowed
            if($this->m_managers->getManagerOf('config')->get('canSubscribe') == '0')
            {
                $this->app()->user()->setFlash($TEXT['Flash_SubscribeImpossible']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
        }

        private function checkConflict($lang, HTTPRequest $request, $registrationsOfUser, $packageNeeded)
        {
            require dirname(__FILE__).'/../../lang/' . $lang . '.php';

            $managerLecture = $this->m_managers->getManagerOf('lecture');

            // TODO: En créant une fonction dans le LectureManager prenant un
            // tableau d'id de package on doit pouvoir optimiser toutes ces
            // requêtes SQL.
            // De la même façon on doit pouvoir améliorer la qualité du code ci-dessous.

            $lectures = array();
            foreach($registrationsOfUser as $reg)
            {
                $lecturesOfPackages = $managerLecture->get($lang, $reg->getIdPackage());

                foreach($lecturesOfPackages as $l)
                    array_push($lectures, $l);
            }

            $lecturesOfPackageNeeded = $managerLecture->get($lang, $packageNeeded->getId());

            foreach($lecturesOfPackageNeeded as $l)
                array_push($lectures, $l);


            // Check all possible conflit
            for($i=0; $i<count($lectures); $i++)
            {
                for($j=($i+1); $j<count($lectures); $j++)
                {
                    if(Lecture::conflict($lectures[$i], $lectures[$j]))
                    {
                        $messageFlash = $TEXT['Flash_SubscribeConflict'];

                        $this->app()->user()->setFlash($messageFlash);
                        $this->app()->httpResponse()->redirect($request->requestURI());
                    }
                }
            }

            // No conflict, continue
        }
    }
?>

