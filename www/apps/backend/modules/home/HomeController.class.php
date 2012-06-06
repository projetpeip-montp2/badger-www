<?php
    class HomeController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeLogout(HTTPRequest $request)
        {
            phpCAS::logout();
        }
    }
?>
