<?php
    class DocumentsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des documents");

            // Archives upload
            if($request->fileExists('zipFile'))
            {
                $idPackage = $request->postData('idPackage');

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
                        $this->app()->httpResponse()->redirect('/admin/documents/index.html');
                    }

                    $zipArchive = new ZipArchive;

                    // Error while opening zip file
                    if($zipArchive->open($fileData['tmp_name']) !== true)
                    {
                        $this->app()->user()->setFlashError('Erreur lors de l\'ouverture de l\'archive.');
                        $this->app()->httpResponse()->redirect('/admin/documents/index.html');
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
                    $this->app()->httpResponse()->redirect('admin/documents/index.html');
                }

                $this->app()->user()->setFlashInfo('Les images contenues dans "' . $fileData['name'] . '" ont été uploadées.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Documents upload
            if($request->fileExists('PDFFile'))
            {
                $sizeLimit = $this->m_managers->getManagerOf('config')->get('documentSizeLimitBackend');

                $idPackage = $request->postData('idPackage');

                $fileData = $request->fileData('PDFFile');

                // Check if a file with the same name exists
                $existingFiles = $this->m_managers->getManagerOf('documentofpackage')->get();
                foreach($existingFiles as $existingFile)
                {
                    if($existingFile->getFileName() == $fileData['name'])
                    {
                        $this->app()->user()->setFlashError('Un document porte déjà le nom: "' . $fileData['name'] . '".');
                        $this->app()->httpResponse()->redirect('/admin/documents/index.html');
                    }
                }

                // Check if the file is successfully uploaded
                if($fileData['error'] == 0 && $fileData['size'] <= $sizeLimit)
                {
                    $packages = $this->m_managers->getManagerOf('package')->get($idPackage);

                    if(count($packages) == 0)
                    {
                        $this->app()->user()->setFlashError('Le package associé n\'existe pas.');
                        $this->app()->httpResponse()->redirect('/admin/documents/index.html');
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
                    $this->app()->httpResponse()->redirect('/admin/documents/index.html');
                }

                $this->app()->user()->setFlashInfo('Le document "' . $fileData['name'] . '" a été uploadé.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            // Display the form

            $packages = $this->m_managers->getManagerOf('package')->get();

            $documents = array();
            $archives = array();

            $documentsManager = $this->m_managers->getManagerOf('documentofpackage');
            $archivesManager = $this->m_managers->getManagerOf('archiveofpackage');

            $packageRequested = false;
            if($request->postExists('packageIdRequested'))
            {
                $packageRequested = true;
                $packageIdRequested = $request->postData('packageIdRequested');
            }

            $found = false;

            if(count($packages) == 0)
            {
                $this->app()->user()->setFlashError('Il n\'y a pas de package dans la base de données.');
                $this->app()->httpResponse()->redirect('/admin/home/index.html');
            }

            foreach($packages as $package)
            {
                if($packageRequested && $packageIdRequested == $package->getId())
                    $found = true;

                $documentOnePackage = $documentsManager->get($package->getId());
                $documents = array_merge($documents, $documentOnePackage);

                $archiveOnePackage = $archivesManager->get($package->getId());
                $archives = array_merge($archives, $archiveOnePackage);
            }

            if($packageRequested && !$found)
            {
                $this->app()->user()->setFlashError('Le package demandé par POST n\'existe pas.');
                $this->app()->httpResponse()->redirect($request->requestURI());
            }

            $this->page()->addVar('packageIdRequested', ($packageRequested ? $packageIdRequested : $packages[0]->getId()) );
            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('documents', $documents);
            $this->page()->addVar('archives', $archives);
        }
    }
?>
