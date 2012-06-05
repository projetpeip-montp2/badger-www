<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class AjaxController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
			$this->app()->httpResponse()->redirect('/home/index.html');
        }
		
		public function executeModifyText(HTTPRequest $request)
		{
			
		}
	}
?>
