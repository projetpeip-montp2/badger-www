<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeShow(HTTPRequest $request)
        {
            $username = $this->app()->user()->getAttribute('logon');

            $managerRegistration = new RegistrationManager;

            $this->page()->addVar('registrations', $managerRegistration->getResgistrationsFromUser($username));



            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerLectures = new LectureManager;

            $lectures = $managerLectures->get($lang, $request->getData('idLecture'));

            $this->page()->addVar('lectures', $lectures);
            $this->page()->addVar('lang', $lang);
            $this->page()->addVar('exists', (count($lectures) == 1));
        }

        public function executeShowAll(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $manager = new LectureManager;

            $this->page()->addVar('lectures', $manager->get($lang, -1));
            $this->page()->addVar('lang', $lang);
        }

        public function executeSubscribe(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            require dirname(__FILE__).'/../../lang/' . $lang . '.php';

            $managerLectures = new LectureManager;

            $lectures = $managerLectures->get($lang, $request->getData('idLecture'));

            $yesOrNo = $request->getData('yesOrNo');

            if(count($lectures) == 1)
            {
                $username = $this->app()->user()->getAttribute('logon');

                $managerRegistration = new RegistrationManager;

                $managerRegistration->subscribe($request->getData('idLecture'), $username, $yesOrNo);

                $this->app()->user()->setFlash($yesOrNo ? $TEXT['Flash_SubscribeOk'] : $TEXT['Flash_UnsubscribeOk']);
            }

            else
                $this->app()->user()->setFlash($yesOrNo ? $TEXT['Flash_SubscribeWrong'] : $TEXT['Flash_UnsubscribeWrong']);

            $this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
        }

        public function executeSchedule()
        {

        }
    }
?>
