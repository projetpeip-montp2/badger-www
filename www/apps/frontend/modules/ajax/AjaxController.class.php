<?php
    class AjaxController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
			$this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
        }
		
		public function executeModifyText(HTTPRequest $request)
		{
			
		}
	}
?>
