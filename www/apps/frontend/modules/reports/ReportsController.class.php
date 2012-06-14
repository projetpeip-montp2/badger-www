<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ReportsController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            if($this->m_managers->getManagerOf('config')->get('canHandleReports') == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_ForbiddenToHandleReports']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }
            
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ReportsIndex']);

            $username = $this->app()->user()->getAttribute('logon');

            $reports = $this->m_managers->getManagerOf('documentofuser')->get(-1, $username);
            $this->page()->addVar('reports', $reports);
        }

        public function executeUpload(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ReportsUpload']);

            if($this->m_managers->getManagerOf('config')->get('canHandleReports') == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_ForbiddenToHandleReports']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            $student = $this->app()->user()->getAttribute('vbmifareStudent');
            $username = $student->getUsername();

            $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);

            $lecturesManager = $this->m_managers->getManagerOf('lecture');
            $packagesManager = $this->m_managers->getManagerOf('package');

            $lectures = array();
            foreach($registrations as $reg)
            {
                $tmp = $lecturesManager->get(-1, $reg->getIdLecture());
                $lectures[] = $tmp[0];
            }

            $packageIds = array();
            $packages = array();
            foreach($lectures as $lecture)
            {
                if(!in_array($lecture->getIdPackage(), $packageIds))
                {
                    $packageIds[] = $lecture->getIdPackage();
                    $tmp = $packagesManager->get($lecture->getIdPackage());
                    $packages[] = $tmp[0];
                }
            }

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoRegistration']);
                $this->app()->httpResponse()->redirect('/reports/index.html');
            }

            if(count($lectures) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoLectures']);
                $this->app()->httpResponse()->redirect('/reports/index.html');
            }

            // Upload report for a package
            if($request->fileExists('reportFile'))
            {
                $sizeLimit = $this->m_managers->getManagerOf('config')->get('reportSizeLimitFrontend');

                $idLecture = $request->postData('idLecture');

                $managerDoc = $this->m_managers->getManagerOf('documentofuser');

                if( count($managerDoc->get($idLecture, $username)) != 0)
                {
                    $this->app()->user()->setFlashError($this->m_TEXT['Flash_AlreadyAReportForALecture']);
                    $this->app()->httpResponse()->redirect('/reports/index.html');
                }

                $fileData = $request->fileData('reportFile');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    $packageName;
                    $lectureName;
                    $found = false;
                    foreach($packages as $pack)
                    {
                        foreach($lectures as $lec)
                        {
                            if($lec->getId() == $idLecture && $lec->getIdPackage() == $pack->getId())
                            {
                                $packageName = $pack->getName('fr');
                                $lectureName = $lec->getName('fr');;
                                $found = true;
                            }
                        }
                    }

                    if(!$found)
                    {
                        $this->app()->user()->setFlashError($this->m_TEXT['Flash_LectureUnknown']);
                        $this->app()->httpResponse()->redirect('/reports/index.html');
                    }


                    $path = dirname(__FILE__).'/../../../../uploads/students/';
                    $filename = $packageName . '_' . $lectureName . '_' .$student->getDepartment() . 
                                $student->getSchoolYear() . '_' . $student->getUsername() .'.pdf';

                    $doc = new DocumentOfUser;
                    $doc->setIdLecture($idLecture);
                    $doc->setIdUser($username);
                    $doc->setFilename($filename);

                    $managerDoc->save($doc);

                    move_uploaded_file($fileData['tmp_name'], $path . $filename);
                }

                else
                {
                    $this->app()->user()->setFlashError($this->m_TEXT['Flash_UploadError']);
                    $this->app()->httpResponse()->redirect('/reports/index.html');
                }

                $this->app()->user()->setFlashInfo($this->m_TEXT['Flash_Uploaded']);
                $this->app()->httpResponse()->redirect('/reports/index.html');
            }

            // Else display the form
            $this->page()->addVar('lang', $this->app()->user()->getAttribute('vbmifareLang'));
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('lectures', $lectures);
        }
    }
?>
