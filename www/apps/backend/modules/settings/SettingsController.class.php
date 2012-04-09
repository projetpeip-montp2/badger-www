<?php
    class SettingsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeMcq(HTTPRequest $request)
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
            }
        }
    }
?>
