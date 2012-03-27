<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeShow(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $manager = new LectureManager;

            $lectures = $manager->get($lang, $request->getData('id'));

            $this->page()->addVar('lectures', $lectures);
            $this->page()->addVar('lang', $lang);
            $this->page()->addVar('exists', (count($lectures) == 1) ? true : false);
        }

        public function executeShowAll(HTTPRequest $request)
        {
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $manager = new LectureManager;

            $this->page()->addVar('lectures', $manager->get($lang, -1));
            $this->page()->addVar('lang', $lang);
        }
    }
?>
