<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class ViewerController extends BackControllerFrontend
    {
        public function ExecuteIndex(HTTPRequest $request)
        {

        }

        public function ExecuteViewImage(HTTPRequest $request)
        {
            $idPackage = $request->getData('idPackage');
            $imageNumber = $request->getData('imageNumber');

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $package = $this->m_managers->getManagerOf('package')->get($idPackage);

            if(count($package) == 0)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';
                $this->app()->user()->setFlashError($TEXT['Flash_PackageUnknown']);
                $this->app()->httpResponse()->redirect('/vbMifare/home/index.html');
            }

            $count = $this->m_managers->getManagerOf('imageofpackage')->count($idPackage);

            if($imageNumber > $count)
            {
                require dirname(__FILE__).'/../../lang/' . $lang . '.php';
                $this->app()->user()->setFlashError($TEXT['Flash_ImageUnknown']);
                $this->app()->httpResponse()->redirect('/vbMifare/home/index.html'); 
            }

            $this->page()->addVar('packageName', $package[0]->getName($lang));
            $this->page()->addVar('idPackage', $idPackage);
            $this->page()->addVar('imageNumber', $imageNumber);
            $this->page()->addVar('count', $count);
        }

        public function ExecuteRedirectToImage(HTTPRequest $request)
        {
            $idPackage = $request->postData('idPackage');
            $imageNumber = $request->postData('imageNumber');

            $this->app()->httpResponse()->redirect('/vbMifare/viewer/viewImage-' . $idPackage . '-' . $imageNumber . '.html'); 
        }
    }
?>
