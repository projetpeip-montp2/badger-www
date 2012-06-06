<?php
    class HomeController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Accueil administration");
        }

        public function executeLogout(HTTPRequest $request)
        {
            phpCAS::logout();
        }
    }
?>
