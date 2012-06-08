<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ReportsController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ReportsIndex']);

            $username = $this->app()->user()->getAttribute('logon');

            $reports = $this->m_managers->getManagerOf('documentofuser')->get(-1, $username);
            $this->page()->addVar('reports', $reports);
        }

        public function executeUpload(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ReportsUpload']);

            $sizeLimit = $this->m_managers->getManagerOf('config')->get('reportSizeLimitFrontend');

            $student = $this->app()->user()->getAttribute('vbmifareStudent');
            $username = $student->getUsername();

            // Upload report for a package
            if($request->fileExists('vbmifareReport'))
            {
                $idPackage = $request->postData('vbmifarePackage');

                $managerDoc = $this->m_managers->getManagerOf('documentofuser');

                if( count($managerDoc->get($idPackage, $username)) != 0)
                {
                    $this->app()->user()->setFlashError($this->m_TEXT['Flash_AlreadyAReportForAPackage']);
                    $this->app()->httpResponse()->redirect('/reports/index.html');
                }

                $fileData = $request->fileData('vbmifareReport');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    $packages = $this->m_managers->getManagerOf('package')->get($idPackage);

                    if(count($packages) == 0)
                    {
                        $this->app()->user()->setFlashError($this->m_TEXT['Flash_PackageUnknown']);
                        $this->app()->httpResponse()->redirect('/reports/index.html');
                    }

                    $path = dirname(__FILE__).'/../../../../uploads/students/';
                    $filename = $packages[0]->getName('fr') . '_' .$student->getDepartment() . 
                                $student->getSchoolYear() . '_' . $student->getUsername() .'.pdf';

                    $doc = new DocumentOfUser;
                    $doc->setIdPackage($idPackage);
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

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_NoPackage']);
                $this->app()->httpResponse()->redirect('/reports/index.html');
            }

            $this->page()->addVar('lang', $lang);
            $this->page()->addVar('packages', $packages);
        }
    }
?>
