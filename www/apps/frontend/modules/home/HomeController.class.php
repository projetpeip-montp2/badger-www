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
            if(!in_array($request->getData('newLang'), explode(';', $this->app()->configGlobal()->get('availablesLanguagesList'))))
                throw new RuntimeException('The language requested is forbiden');

            $this->app->user()->setAttribute('lang', $request->getData('newLang'));

            $this->app->httpResponse()->redirect($request->getData('previousPage'));
        }
    }
?>
