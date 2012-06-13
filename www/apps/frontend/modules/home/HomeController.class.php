<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class HomeController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_HomeIndex']);
        }

        public function executeGuide(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_HomeGuide']);
        }

        public function executeLegalNotice(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_HomeLegalNotice']);
        }

        public function executeLogout(HTTPRequest $request)
        {
            // Absolutely don't remove it!
            $user = $this->app()->user();
            $user->unsetAttribute('logon');
            $user->unsetAttribute('logDone');
            $user->unsetAttribute('vbmifareStudent');
            $user->unsetAttribute('vbmifareLang');
            phpCAS::logout();
        }

        public function executeChangeLang(HTTPRequest $request)
        {
            // Check that the lang sent by URL is available
            if(!in_array($request->getData('newLang'), explode(';', $this->m_managers->getManagerOf('config')->get('availablesLanguagesList'))))
                throw new RuntimeException('The language requested is forbiden');

            $this->app()->user()->setAttribute('vbmifareLang', $request->getData('newLang'));

            // Redirect on the previous page (sent by the current URL)
            $this->app()->httpResponse()->redirect($request->getData('previousPage'));
        }
    }
?>
