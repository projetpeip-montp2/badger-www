<?php
    class PackagesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Gestions des packages");

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            $this->page()->addVar('packages', $packages);
        }

        public function executeAddPackages(HTTPRequest $request)
        {
            // If the form containing the filepath exist (aka the form is
            // submitted)
            if ($request->fileExists('CSVFile'))
            {
                $fileData = $request->fileData('CSVFile');

                // Check if the file is sucefully uploaded
                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $packages = array();

                    // Parsing package here from CSV file
                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 5)
                        {
                            $this->app()->user()->setFlashError('Le fichier n\'a pas 5 colonnes');
                            $this->app()->httpResponse()->redirect($request->requestURI());
                            break;
                        }
        
                        $package = new Package;
                        $package->setCapacity($lineDatas[0]);
                        $package->setRegistrationsCount(0);
                        $package->setName('fr', $lineDatas[1]);
                        $package->setName('en', $lineDatas[2]);
                        $package->setDescription('fr', $lineDatas[3]);
                        $package->setDescription('en', $lineDatas[4]);

                        array_push($packages, $package);
                    }

                    fclose($file);

                    // Save all packages parsed
                    $manager = $this->m_managers->getManagerOf('package');
                    $manager->save($packages);

                    $this->app()->user()->setFlashInfo('Fichier uploadé');
                }

                else
                    $this->app()->user()->setFlashError('Erreur durant l\'upload du fichier');
            }

            $this->app()->httpResponse()->redirect('/admin/packages/index.html');
        }

/*
        private function deletePackageDocuments($packageId)
        {
            // Delete associated documents and images
            $path = dirname(__FILE__).'/../../../../uploads/admin/';
            
            $managerDocuments = $this->m_managers->getManagerOf('documentofpackage');
            $documents = $managerDocuments->get($packageId);

            foreach($documents as $document)
                unlink($path . 'pdf/' . $document->getFilename());

            // Delete images on server
            for($i = 1; $i <= $count; $i++)
            {
                $filename = 'images/' . $packageId . '_' . $i . '.jpg';
                unlink($path . $filename);
            }
        }
*/
    }
?>
