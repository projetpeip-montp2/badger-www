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

            $managerRegistration = $this->m_managers->getManagerOf('registration');
            $registrationsId = $managerRegistration->getResgistrationsIdFromUser($username);

            $managerPackage = $this->m_managers->getManagerOf('package');
            $packages = $managerPackage->get($lang, $request->getData('idPackage'));

            if(count($packages) != 1)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $this->app()->user()->setFlash($TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/vbMifare/lectures/showAll.html');
            }

            $package = $packages[0];

            $wantSubscribe = !in_array($package->getId(), $registrationsId);

            if($request->postExists('isSubmitted'))
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                if(!$this->canSubscribe())
                {
                    $this->app()->user()->setFlash($TEXT['Flash_SubscribeImpossible']);
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                // TODO: Avant d'enregistrer l'inscription, vérifier s'il n'y a pas de conflit.

                $managerRegistration->subscribe($request->getData('idPackage'), $username, $wantSubscribe ? 1 : 0);

                $this->app()->user()->setFlash($wantSubscribe ? $TEXT['Flash_SubscribeOk'] : $TEXT['Flash_UnsubscribeOk']);

                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('wantSubscribe', $wantSubscribe);

            $this->page()->addVar('package', $package);
            $this->page()->addVar('lang', $lang);

            $managerLecture = $this->m_managers->getManagerOf('lecture');
            $this->page()->addVar('lectures', $managerLecture->get($lang, $request->getData('idPackage')));
        }

        public function executeShowAll(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackage = $this->m_managers->getManagerOf('package');

            $this->page()->addVar('packages', $managerPackage->get($lang, -1));
            $this->page()->addVar('lang', $lang);
        }

        public function executeSchedule()
        {

        }






        private function canSubscribe()
        {
            return ($this->app()->user()->getAttribute('vbmifareStudent')->getMCQStatus() == 'CanTakeMCQ') &&
                   ($this->app()->configGlobal()->get('canSubscribe') != '0');
        }
    }
?>

