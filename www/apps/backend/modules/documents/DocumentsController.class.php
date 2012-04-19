<?php
    class DocumentsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeUploadPDF(HTTPRequest $request)
        {
            // Handle POST data
            // Add PDF file to uploads folder
            
//dirname(__FILE__) . '/../../../../uploads/admin/pdf'
            // Else display the form

            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader des fichiers PDF.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/lectures/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }
    }
?>
