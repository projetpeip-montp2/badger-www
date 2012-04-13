<?php
    class SettingsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeChangeAvailableStudents(HTTPRequest $request)
        {

        }

        public function executeChangeAvailableAdmins(HTTPRequest $request)
        {
            if($request->postExists('adminList'))
            {
                $newAdminList = $this->app()->user()->getAttribute('logon') . ';' . $request->postData('adminList');

                $this->app()->configLocal()->replace('adminsList', $newAdminList);

                $this->app()->user()->setFlash('Admin list changed for "' . $newAdminList . '".');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
        }

        public function executeChangeSubscribesStatus(HTTPRequest $request)
        {
            $authorized = $this->app()->configGlobal()->get('canSubscribe') != 0;

            if($request->postExists('isSubmitted'))
            {
                $this->app()->configGlobal()->replace('canSubscribe', $authorized ? 0 : 1);
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('authorized', $authorized);
        }
    }
?>
