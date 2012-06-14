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
            if($this->m_managers->getManagerOf('config')->get('canHandleReports') == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_ForbiddenToHandleReports']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ReportsUpload']);

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            $managerLectures = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLectures->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoPackage']);
                $this->app()->httpResponse()->redirect('/reports/index.html');
            }

            if(count($lectures) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoLectures']);
                $this->app()->httpResponse()->redirect('/reports/index.html');
            }

            $sizeLimit = $this->m_managers->getManagerOf('config')->get('reportSizeLimitFrontend');

            $student = $this->app()->user()->getAttribute('vbmifareStudent');
            $username = $student->getUsername();

            // Upload report for a package
            if($request->fileExists('reportFile'))
            {
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
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $this->page()->addVar('lang', $lang);
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('lectures', $lectures);
        }
    }
?>
