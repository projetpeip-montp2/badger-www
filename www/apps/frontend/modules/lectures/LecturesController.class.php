<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class LecturesController extends BackControllerFrontend
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
            $registrationsOfUser = $managerRegistration->getRegistrationsFromUser($username);

            // Retrieve the package given by id in URL
            $managerPackage = $this->m_managers->getManagerOf('package');
            $packages = $managerPackage->get($request->getData('idPackage'));

            // Check that the package exists
            if(count($packages) != 1)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $this->app()->user()->setFlashError($TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/lectures/showAll.html');
            }

            $package = $packages[0];
            $managerLecture = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLecture->get( $package->getId() );

            $wantSubscribe = true;
            foreach($registrationsOfUser as $reg)
            {
                foreach($lectures as $lec)
                {
                    if($reg->getIdLecture() == $lec->getId() )
                        $wantSubscribe = false;
                }
            }

            // If the form is submitted, do the registration
            if($request->postExists('isSubmitted'))
            {
                $this->checkSubscribe($request);

                if($wantSubscribe)
                {
                    $this->checkRegistrationsCount($lang, $request, $package);
                    $this->checkConflict($lang, $request, $registrationsOfUser, $package);
                }

                foreach($lectures as $lecture)
                    $managerRegistration->subscribe($request->getData('idPackage'), $lecture->getId(), $username, $wantSubscribe ? 1 : 0);

                $package->setRegistrationsCount($package->getRegistrationsCount() + ($wantSubscribe ? 1 : -1));

                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $this->app()->user()->setFlashInfo($wantSubscribe ? $TEXT['Flash_SubscribeOk'] : $TEXT['Flash_UnsubscribeOk']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else display the form
            $this->page()->addVar('wantSubscribe', $wantSubscribe);

            $this->page()->addVar('package', $package);
            $this->page()->addVar('lang', $lang);

            $this->page()->addVar('registrationsAllowed', $this->m_managers->getManagerOf('config')->get('canSubscribe') == '1');
            $this->page()->addVar('lectures', $lectures);

            $counter = $this->m_managers->getManagerOf('documentofpackage')->count($request->getData('idPackage'));
            $this->page()->addVar('showDocuments', $counter != 0);
            $counter = $this->m_managers->getManagerOf('imageofpackage')->count($request->getData('idPackage'));
            $this->page()->addVar('showImages', $counter != 0);
        }

        public function executeShowDocuments(HTTPRequest $request)
        {
            $idPackage = $request->getData('idPackage');

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $package = $this->m_managers->getManagerOf('package')->get($idPackage);

            if(count($package) == 0)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';
                $this->app()->user()->setFlashError($TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            // Send package name if it exists
            $this->page()->addVar('packageName', $package[0]->getName($lang));
            $this->page()->addVar('documents', $this->m_managers->getManagerOf('documentofpackage')->get($idPackage));
        }

        public function executeShowAll(HTTPRequest $request)
        {
            // Display all packages
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackage = $this->m_managers->getManagerOf('package');

            $this->page()->addVar('packages', $managerPackage->get());
            $this->page()->addVar('lang', $lang);
        }

        public function executeShowSubscribed(HTTPRequest $request)
        {
            // Display all packages subscribed
            $username = $this->app()->user()->getAttribute('logon');

            // Retrieve registration id of the users
            $registrationsOfUser = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackage = $this->m_managers->getManagerOf('package');

            $packages = array();
            foreach($registrationsOfUser as $reg)
                $packages = array_merge($packages, $managerPackage->get($reg->getIdPackage(), -1));

            $packages = array_unique($packages, SORT_REGULAR);

            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('lang', $lang);
        }

        public function executeSchedule()
        {
            $username = $this->app()->user()->getAttribute('logon');

            $registrationsOfUser = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);

            $managerLectures = $this->m_managers->getManagerOf('lecture');

            $result = array();
            foreach($registrationsOfUser as $reg)
            {
                $lecture = $managerLectures->get($reg->getIdPackage(), $reg->getIdLecture());
                if(!array_key_exists($lecture[0]->getDate()->__toString(), $result))
                    $result[$lecture[0]->getDate()->__toString()] = array($lecture[0]);
                else
                    $result[$lecture[0]->getDate()->__toString()][] = $lecture[0];
            }

            $this->page()->addVar('classrooms', $this->m_managers->getManagerOf('classroom')->get());
            $this->page()->addVar('availabilities', $this->m_managers->getManagerOf('availability')->get());

            $this->page()->addVar('registrations', $registrationsOfUser);
            $this->page()->addVar('lectures', $this->sort($result));
            $this->page()->addVar('lang', $this->app()->user()->getAttribute('vbmifareLang'));
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

                $this->app()->user()->setFlashError($flashMessage);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }


            // Check if registrations are allowed
            if($this->m_managers->getManagerOf('config')->get('canSubscribe') == '0')
            {
                $this->app()->user()->setFlashError($TEXT['Flash_SubscribeImpossible']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
        }

        private function checkRegistrationsCount($lang, HTTPRequest $request, $packageNeeded)
        {
            require dirname(__FILE__).'/../../lang/' . $lang . '.php';

            if($packageNeeded->getRegistrationsCount() + 1 > $packageNeeded->getCapacity())
            {
                $this->app()->user()->setFlashError($TEXT['Flash_NoPlace']);
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
                $lecturesOfRegistration = $managerLecture->get(-1, $reg->getIdLecture());

                foreach($lecturesOfRegistration as $l)
                    array_push($lectures, $l);
            }

            $lecturesOfPackageNeeded = $managerLecture->get($packageNeeded->getId());

            foreach($lecturesOfPackageNeeded as $l)
                array_push($lectures, $l);

            // Check all possible conflit
            for($i=0; $i<count($lectures); $i++)
            {
                for($j=($i+1); $j<count($lectures); $j++)
                {
                    if(Lecture::conflict($lectures[$i], $lectures[$j]))
                    {
                        // TODO: Ajouter dans le message flash avec qui y a conflit.
                        $messageFlash = $TEXT['Flash_SubscribeConflict'];

                        $this->app()->user()->setFlashError($messageFlash);
                        $this->app()->httpResponse()->redirect($request->requestURI());
                    }
                }
            }

            // No conflict, continue
        }

        private	function sort($array)
        {
    	    uksort($array, "dateCompare");
            return $array;
	    }
    }

function dateCompare($string1, $string2)
{
    $date1 = new Date;
    $date2 = new Date;
    $date1->setFromString($string1);
    $date2->setFromString($string2);
    return Date::compare($date1, $date2);
}
?>

