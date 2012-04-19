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
                    $this->app()->user()->setFlashError('Problème lors de l\'upload de "' . $fileData['name'] . '".');
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

        public function executeUploadImages(HTTPRequest $request)
        {
            // Handle POST data
            // Extract zip file and add images to uploads/admin/images folder

            if($request->fileExists('zipFile'))
            {
                $idPackage = $request->postData('PackageList');

                // Check if a package already has some images
                if(count($this->m_managers->getManagerOf('imageofpackage')->get($idPackage)) > 0)
                {
                    $this->app()->user()->setFlashError('Il est nécessaire de supprimer les images déjà présentes pour cette conférences.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadImages.html');
                }

                $sizeLimit = $this->m_managers->getManagerOf('config')->get('zipFileSizeLimitBackend');

                $fileData = $request->fileData('zipFile');

                // Check if the file is successfully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    $packages = $this->m_managers->getManagerOf('package')->get($idPackage);

                    if(count($packages) == 0)
                    {
                        $this->app()->user()->setFlashError('Le package associé n\'existe pas.');
                        $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadImages.html');
                    }

                    $archive = new ZipArchive;

                    // Error while opening zip file
                    if($archive->open($fileData['tmp_name']) !== true)
                    {
                        $this->app()->user()->setFlashError('Erreur lors de l\'ouverture de l\'archive.');
                        $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadImages.html');
                    }

                    $path = dirname(__FILE__).'/../../../../uploads/admin/images/';

                    $managerImages = $this->m_managers->getManagerOf('imageofpackage');
                    $image = new ImageOfPackage;

                    for($i = 0; $i < $archive->numFiles; $i++)
                    {
                        // Retrieve filenames contained in the zip
                        $filenameZip = $archive->getNameIndex($i);

                        // Extract them to uploads/images and rename to follow "idPackage_$i.jpg" format
                        $archive->extractTo($path, $filenameZip);
                        $imagename = $idPackage . '_' . ($i + 1) . '.jpg';
                        rename($path . $filenameZip, $path . $imagename);

                        // Save in database the record
                        $image->setIdPackage($idPackage);
                        $image->setFilename($imagename);
                        $managerImages->save($image);
                    }
                }
                else
                {
                    $this->app()->user()->setFlashError('Problème lors de l\'upload des images contenues dans "' . $fileData['name'] . '".');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadImages.html');
                }

                $this->app()->user()->setFlashInfo('Les images contenues dans "' . $fileData['name'] . '" ont été uploadées.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadImages.html');
            }
            
            // Else display the form

            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader un fichier zip.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/documents/uploadImages.html');
            }

            $this->page()->addVar('packages', $packages);
        }

        public function executeDeletePDF(HTTPRequest $request)
        {

        }

        public function executeDeleteImages(HTTPRequest $request)
        {

        }
    }
?>