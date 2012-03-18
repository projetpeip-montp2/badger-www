<?php
    class HomeController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeGuide(HTTPRequest $request)
        {

        }

        public function executeChangeLang(HTTPRequest $request)
        {
            $this->app->user()->setAttribute('lang', $request->getData('newLang'));

            $this->app->httpResponse()->redirect('/home/index.html');
        }
    }
?>
