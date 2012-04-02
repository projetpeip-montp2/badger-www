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

            $managerLectures = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLectures->get($lang, $request->getData('idLecture'));

            if(count($lectures) != 1)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $this->app()->user()->setFlash($TEXT['Flash_LectureUnknown']);
                $this->app()->httpResponse()->redirect('/vbMifare/lectures/showAll.html');
            }

            $lecture = $lectures[0];

            $wantSubscribe = !in_array($lecture->getId(), $registrationsId);

            if($request->postExists('isSubmitted'))
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';

                $managerRegistration->subscribe($request->getData('idLecture'), $username, $wantSubscribe ? 1 : 0);

                $this->app()->user()->setFlash($wantSubscribe ? $TEXT['Flash_SubscribeOk'] : $TEXT['Flash_UnsubscribeOk']);

                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('wantSubscribe', $wantSubscribe);

            $this->page()->addVar('lecture', $lecture);
            $this->page()->addVar('lang', $lang);
        }

        public function executeShowAll(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $manager = $this->m_managers->getManagerOf('lecture');

            $this->page()->addVar('lectures', $manager->get($lang, -1));
            $this->page()->addVar('lang', $lang);
        }

        public function executeSchedule()
        {

        }
    }
?>
