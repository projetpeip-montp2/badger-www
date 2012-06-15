<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ViewerController extends BackControllerFrontend
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ViewerIndex']);

            // Display the form
            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashInfo($this->m_TEXT['Viewer_NoPackage']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }

            $idPackage = $request->postExists('packageIdRequested') ? $request->postData('packageIdRequested') : $packages[0]->getId();

            $found = false;
            foreach($packages as $pack)
            {
                if($pack->getId() == $idPackage)
                    $found = true;
            }

            if(!$found)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Viewer_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/home/index.html');
            }


            $archives = $this->m_managers->getManagerOf('archiveofpackage')->get($idPackage);

            $this->page()->addVar('idPackage', $idPackage);
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('archives', $archives);
        }

        public function executeViewImage(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', $this->m_TEXT['Title_ViewerViewImage']);
            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $idPackage = $request->getData('idPackage');
            $idArchive = $request->getData('idArchive');
            $imageNumber = $request->getData('imageNumber');

            $package = $this->m_managers->getManagerOf('package')->get($idPackage);
            $archive = $this->m_managers->getManagerOf('archiveofpackage')->get($idPackage);

            if(count($package) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/viewer/index.html');
            }
            if(count($archive) == 0)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_ArchiveUnknown']);
                $this->app()->httpResponse()->redirect('/viewer/index.html');
            }

            $count = $this->m_managers->getManagerOf('imageofarchive')->count($idArchive);

            if($imageNumber > $count)
            {
                $this->app()->user()->setFlashError($this->m_TEXT['Flash_ImageUnknown']);
                $this->app()->httpResponse()->redirect('/home/index.html'); 
            }

            $this->page()->addVar('packageName', $package[0]->getName($lang));
            $this->page()->addVar('archiveName', $archive[0]->getFilename());
            $this->page()->addVar('idPackage', $idPackage);
            $this->page()->addVar('idArchive', $idArchive);
            $this->page()->addVar('imageNumber', $imageNumber);
            $this->page()->addVar('count', $count);
        }

        public function executeRedirectToImage(HTTPRequest $request)
        {
			$this->page()->setIsAjaxPage(TRUE);

            $idPackage = $request->postData('idPackage');
            $idArchive = $request->postData('idArchive');
            $imageNumber = $request->postData('imageNumber');

            $this->app()->httpResponse()->redirect('/viewer/viewImage-' . $idPackage . '-' . $idArchive . '-' . $imageNumber . '.html');
        }
    }
?>
