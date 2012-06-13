<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ErrorsController extends BackControllerFrontend
    {
        public function execute403(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Error 403');
        }

        public function execute404(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Error 404');
        }
    }
?>
