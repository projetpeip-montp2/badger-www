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
            if($request->fileExists('PDFFile'))
            {
                $sizeLimit = $this->m_managers->getManagerOf('config')->get('documentSizeLimitBackend');

                $idPackage = $request->postData('PackageList');

                $fileData = $request->fileData('PDFFile');

                // Check if a file with the same name exists
                $existingFiles = $this->m_managers->getManagerOf('documentofpackage')->get();
                foreach($existingFiles as $existingFile)
                {
                    if($existingFile->getFileName() == $fileData['name'])
                    {
                        $this->app()->user()->setFlashError('Un document porte déjà le nom: "' . $fileData['name'] . '".');
                        $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadPDF.html');
                    }
                }

                // Check if the file is successfully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    $packages = $this->m_managers->getManagerOf('package')->get($idPackage);

                    if(count($packages) == 0)
                    {
                        $this->app()->user()->setFlashError('Le package associé n\'existe pas.');
                        $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadPDF.html');
                    }

                    $path = dirname(__FILE__).'/../../../../uploads/admin/pdf/';

                    $document = new DocumentOfPackage;
                    $document->setIdPackage($idPackage);
                    $document->setFilename($fileData['name']);

                    $this->m_managers->getManagerOf('documentofpackage')->save($document);

                    move_uploaded_file($fileData['tmp_name'], $path . $fileData['name']);
                }
                else
                {
                    $this->app()->user()->setFlashError('Problème lors de l\'upload de ' . $fileData['name']);
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadPDF.html');
                }

                $this->app()->user()->setFlashInfo('Le document "' . $fileData['name'] . '" a été uploadé.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadPDF.html');
            }

            // Else display the form

            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader des fichiers PDF.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadPDF.html');
            }

            $this->page()->addVar('packages', $packages);
        }
    }
?>
