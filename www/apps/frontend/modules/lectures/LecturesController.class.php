<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class LecturesController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_LectureIndex']);
        }

        public function executeShow(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_LectureShow']);

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
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/lectures/showAll.html');
            }

            $package = $packages[0];
            $managerLecture = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLecture->get( $package->getId() );

            $managerDocOfUser = $this->m_managers->getManagerOf('documentofuser');

            $wantSubscribe = true;
            $haveReportsForThisPackage = false;
            foreach($registrationsOfUser as $reg)
            {
                foreach($lectures as $lec)
                {
                    $reports = $managerDocOfUser->get($lec->getId(), $username);
                    if(!empty($reports))
                        $haveReportsForThisPackage = true;

                    if($reg->getIdLecture() == $lec->getId() )
                        $wantSubscribe = false;
                }
            }

            // If the form is submitted, do the registration
            if($request->postExists('isSubmitted'))
            {
                if($wantSubscribe)
                {
                    $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);
                    $packagesCount = $this->countSelectedPackages($registrations);

                    // The user cannot subscribe to more than the fixed number of packages
                    if($packagesCount >= $this->m_managers->getManagerOf('config')->get('packageRegistrationsCount'))
                    {
                        $this->app()->user()->setFlashError($this->m_TEXT['Package_MaxSubscriptions']);
                        $this->app()->httpResponse()->redirect($request->requestURI());
                    }
                }

                if( count($lectures) == 0 )
                {
                    $this->app()->user()->setFlashError($this->m_TEXT['Package_NoLecture']);
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $this->checkSubscribe($request);

                if($wantSubscribe)
                {
                    $this->checkRegistrationsCount($lang, $request, $package);
                    $this->checkConflict($lang, $request, $registrationsOfUser, $package);
                }

                foreach($lectures as $lecture)
                    $managerRegistration->subscribe($request->getData('idPackage'), $lecture->getId(), $username, $wantSubscribe ? 1 : 0);

                $package->setRegistrationsCount($package->getRegistrationsCount() + ($wantSubscribe ? 1 : -1));

                $this->app()->user()->setFlashInfo($wantSubscribe ? $this->m_TEXT['Flash_SubscribeOk'] : $this->m_TEXT['Flash_UnsubscribeOk']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Else display the form
            $this->page()->addVar('haveReportsForThisPackage', $haveReportsForThisPackage);
            $this->page()->addVar('wantSubscribe', $wantSubscribe);

            $this->page()->addVar('wantSubscribe', $wantSubscribe);

            $this->page()->addVar('package', $package);
            $this->page()->addVar('lang', $lang);

            $this->page()->addVar('registrationsAllowed', $this->m_managers->getManagerOf('config')->get('canSubscribe') == '1');
            $this->page()->addVar('lectures', $lectures);

            $counter = $this->m_managers->getManagerOf('documentofpackage')->count($request->getData('idPackage'));
            $this->page()->addVar('showDocuments', $counter != 0);
            $counter = $this->m_managers->getManagerOf('archiveofpackage')->count($request->getData('idPackage'));
            $this->page()->addVar('showImages', $counter != 0);
        }

        public function executeShowDocuments(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_LectureShowDocuments']);

            $idPackage = $request->getData('idPackage');

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $package = $this->m_managers->getManagerOf('package')->get($idPackage);

            if(count($package) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            // Send package name if it exists
            $this->page()->addVar('packageName', $package[0]->getName($lang));
            $this->page()->addVar('documents', $this->m_managers->getManagerOf('documentofpackage')->get($idPackage));
        }

        public function executeDownloadDocuments(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $idPackage = $request->getData('idPackage');

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $package = $this->m_managers->getManagerOf('package')->get($idPackage);

            if(count($package) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            $packageName = $package[0]->getName($lang);
            $documents = $this->m_managers->getManagerOf('documentofpackage')->get($idPackage);


            $zip = new zipfile();

            foreach($documents as $doc)
            {
                $filename = dirname(__FILE__).'/../../../../uploads/admin/pdf/' . $doc->getFilename();

                $fo = fopen($filename, 'r');
                if(!$fo)
                {
                    $this->app()->user()->setFlashError($this->m_TEXT['Flash_DownloadDocumentsError']);
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $contenu = fread($fo, filesize($filename));
                fclose($fo);

                $zip->addfile($contenu, $doc->getFilename());
            }

            header('Content-Type: application/x-zip');
            header('Content-Disposition: inline; filename=' . $packageName . '.zip');

            echo $zip->file();
        }

        public function executeShowAll(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_LectureShowAll']);

            // Display all packages
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackage = $this->m_managers->getManagerOf('package');
            $packages = $managerPackage->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoPackage']);
                $this->app()->httpResponse()->redirect('/lectures/index.html');
            }

            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('lang', $lang);
        }

        public function executeShowSubscribed(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_LectureShowSubscribed']);

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

        public function executeSchedule(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_LectureSchedule']);

            $username = $this->app()->user()->getAttribute('logon');

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

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

            $canViewPlanning = ($this->m_managers->getManagerOf('config')->get('canViewPlanning') != 0);

            $classrooms = $this->m_managers->getManagerOf('classroom')->get();
            $availabilities = $this->m_managers->getManagerOf('availability')->get();
            $lectures = $this->sort($result);

            $output = '';
            $output .= '<ul>';
            // Display all day with lecture
            foreach($lectures as $key => $lecture)
            {
                $output .= '<li>' . $key . '</li>';
                $output .= '<ul>';
                // Display lecture in this day
                foreach($lecture as $lect)
                {
                    $output .= '<li>' . $lect->getName($lang) . '</li>';
                    $output .= '<ul>';
                    // Display informations for this lecture
                    foreach($registrationsOfUser as $reg)
                    {
                        if($lect->getId() == $reg->getIdLecture())
                            $output .= '<li>' . $this->m_TEXT['Planning_RegistrationStatus'] . ': ' . $this->m_TEXT['Planning_' . $reg->getStatus()] . '</li>';
                    }
                    
                    $idAvailability = $lect->getIdAvailability();
                    $room = ($idAvailability == 0) ? $this->m_TEXT['Planning_NoClassroom'] : $this->getClassroomName($classrooms, $availabilities, $idAvailability);

                    $output .= '<li>' . $this->m_TEXT['Planning_Classroom'] . ': ' . $room . '</li>';
                    $output .= '<li>' . $this->m_TEXT['Lecture_StartTime'] . ': ' . $lect->getStartTime() . '</li>';
                    $output .= '<li>' . $this->m_TEXT['Lecture_EndTime'] . ': ' . $lect->getEndTime() . '</li>';
                    $output .= '</ul>';
                }
                $output .= '</ul>';
            }
            $output .='</ul>';

            if($canViewPlanning && $request->postExists($this->m_TEXT['Form_Send']))
            {
                $mailAdress = $username . $this->m_managers->getManagerOf('config')->get('mailAppendix');

                // Headers to send the mail correctly
                $headers = 'From: ' . $this->m_managers->getManagerOf('config')->get('mailSender') . "\r\n";
                $headers .= 'Mime-Version: 1.0'."\r\n";
                $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
                $headers .= "\r\n";

                mail($mailAdress, $this->m_TEXT['Lecture_MailTitle'], $this->m_TEXT['Lecture_MailIntro'] . $output, $headers);
            }

            $this->page()->addVar('canViewPlanning', $canViewPlanning);
            $this->page()->addVar('output', $output);
        }

        private function checkSubscribe(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $status = $this->app()->user()->getAttribute('vbmifareStudent')->getMCQStatus();

            // Check is user's status is allowed for registrations
            if($status != 'CanTakeMCQ')
            {
                $flashMessage = '';

                switch($status)
                {
                case 'Visitor':
                    $flashMessage = $this->m_TEXT['Flash_SubscribeVisitor'];
                    break;

                case 'Generated':
                    $flashMessage = $this->m_TEXT['Flash_SubscribeGenerated'];
                    break;

                case 'Taken':
                    $flashMessage = $this->m_TEXT['Flash_SubscribeTaken'];
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
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_SubscribeImpossible']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
        }

        private function checkRegistrationsCount($lang, HTTPRequest $request, $packageNeeded)
        {
            if($packageNeeded->getRegistrationsCount() + 1 > $packageNeeded->getCapacity())
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoPlace']);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
        }

        private function checkConflict($lang, HTTPRequest $request, $registrationsOfUser, $packageNeeded)
        {
            $managerLecture = $this->m_managers->getManagerOf('lecture');

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
                    if(Tools::conflict($lectures[$i], $lectures[$j]))
                    {
                        $messageFlash = $this->m_TEXT['Flash_SubscribeConflict'];

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

        private function getClassroomName($classrooms, $availabilities, $idAvailability)
        {
            $result = 'Unknown classroom';

            foreach($availabilities as $avail)
            {
                if($avail->getId() == $idAvailability)
                {
                    foreach($classrooms as $room)
                    {
                        if($room->getId() == $avail->getIdClassroom())
                            $result = $room->getName();
                    }
                }
            }

            return $result;
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
