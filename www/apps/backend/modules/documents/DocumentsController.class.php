<?php
    class DocumentsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des documents");
        }

        public function executeUploadPDF(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des PDF");

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
                        $this->app()->httpResponse()->redirect('/admin/documents/uploadPDF.html');
                    }
                }

                // Check if the file is successfully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    $packages = $this->m_managers->getManagerOf('package')->get($idPackage);

                    if(count($packages) == 0)
                    {
                        $this->app()->user()->setFlashError('Le package associé n\'existe pas.');
                        $this->app()->httpResponse()->redirect('/admin/documents/uploadPDF.html');
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
                    $this->app()->httpResponse()->redirect('/admin/documents/uploadPDF.html');
                }

                $this->app()->user()->setFlashInfo('Le document "' . $fileData['name'] . '" a été uploadé.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }

            // Else display the form

            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader des fichiers PDF.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }

        public function executeUploadImages(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Uploader des images");

            // Handle POST data
            // Extract zip file and add images to uploads/admin/images folder
            if($request->fileExists('zipFile'))
            {
                $idPackage = $request->postData('PackageList');

                // Check that a filename was typed
                if($request->postData('filename') == '')
                {
                    $this->app()->user()->setFlashError('Il faut entrer un nom pour le fichier.');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $sizeLimit = $this->m_managers->getManagerOf('config')->get('zipFileSizeLimitBackend');

                $fileData = $request->fileData('zipFile');

                // Check if the file is successfully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    // Check if the package exists
                    if(count($this->m_managers->getManagerOf('package')->get($idPackage)) == 0)
                    {
                        $this->app()->user()->setFlashError('Le package associé n\'existe pas.');
                        $this->app()->httpResponse()->redirect($request->requestURI());
                    }

                    $zipArchive = new ZipArchive;

                    // Error while opening zip file
                    if($zipArchive->open($fileData['tmp_name']) !== true)
                    {
                        $this->app()->user()->setFlashError('Erreur lors de l\'ouverture de l\'archive.');
                        $this->app()->httpResponse()->redirect($request->requestURI());
                    }

                    $path = dirname(__FILE__).'/../../../../uploads/admin/images/';

                    // Create the archive and insert it in the database
                    $archive = new ArchiveOfPackage;
                    $archive->setIdPackage($idPackage);
                    $archive->setFilename($request->postData('filename'));

                    $this->m_managers->getManagerOf('archiveofpackage')->save($archive);

                    // Retrive the id of the inserted archive
                    $idArchive = $this->m_managers->getManagerOf('archiveofpackage')->lastInsertId();

                    $imagesManager = $this->m_managers->getManagerOf('imageofarchive');
                    $image = new ImageOfArchive;

                    for($i = 0; $i < $zipArchive->numFiles; $i++)
                    {
                        // Retrieve filenames contained in the zip
                        $filenameZip = $zipArchive->getNameIndex($i);

                        // Extract them to uploads/admin/images and rename to follow "idPackage_idArchive_i.jpg" format
                        $zipArchive->extractTo($path, $filenameZip);
                        $imagename = $idPackage . '_' . $idArchive . '_' . ($i + 1) . '.jpg';
                        rename($path . $filenameZip, $path . $imagename);

                        // Save in database the record
                        $image->setIdArchive($idArchive);
                        $image->setFilename($imagename);

                        $imagesManager->save($image);
                    }
                }
                else
                {
                    $this->app()->user()->setFlashError('Problème lors de l\'upload des images contenues dans "' . $fileData['name'] . '".');
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $this->app()->user()->setFlashInfo('Les images contenues dans "' . $fileData['name'] . '" ont été uploadées.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }
            
            // Else display the form
            $packages = $this->m_managers->getManagerOf('package')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader un fichier zip.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('packages', $packages);
        }

        public function executeDeletePDF(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Supprimer des PDF");

            // Handle POST data
            // Delete document
            if($request->postExists('Supprimer'))
            {
                // Server
                $documents = $this->m_managers->getManagerOf('documentofpackage')->get(-1, $request->postData('documentId'));
                $path = dirname(__FILE__).'/../../../../uploads/admin/pdf/';
                unlink($path . $documents[0]->getFilename());

                // Database
                $filename = $this->m_managers->getManagerOf('documentofpackage')->delete($request->postData('documentId'));

                // Redirection
                $this->app()->user()->setFlashInfo('Le document "' . $request->postData('DocumentName') . '" du package "' . $request->postData('PackageName') . '" a été supprimé.');
                $this->app()->httpResponse()->redirect('/admin/documents/deletePDF.html');
            }
            // Else display the form
            $packages = $this->m_managers->getManagerOf('package')->get();
            $documents = $this->m_managers->getManagerOf('documentofpackage')->get();

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de packages dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }
            if(count($documents) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de document dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }

            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('documents', $documents);
        }

        public function executeDeleteImages(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Supprimer des images");

            // Handle POST data
            // Delete images
            if($request->postData('Supprimer'))
            {
                $packageId = $request->postData('packageId');

                $managerImages = $this->m_managers->getManagerOf('imageofpackage');
                $count = $managerImages->count($packageId);

                // Delete in database
                $managerImages->delete($packageId);

                $path = dirname(__FILE__).'/../../../../uploads/admin/images/';

                // Delete on server
                for($i = 1; $i <= $count; $i++)
                {
                    $filename = $packageId . '_' . $i . '.jpg';
                    unlink($path . $filename);
                }

                // Redirection
                $this->app()->user()->setFlashInfo('Les ' . $count . ' images du package "' . $request->postData('PackageName') . '" ont été supprimées.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }
            // Else display the form

            $packages = $this->m_managers->getManagerOf('package')->get();
            $images = $this->m_managers->getManagerOf('imageofpackage')->get();
            
            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il faut au moins un package pour pouvoir uploader un fichier zip.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }
            if(count($images) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas d\'images dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/documents/index.html');
            }

            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('images', $images);
        }
    }
?>
