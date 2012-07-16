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

        public function executeRegistrationBatch(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Inscription par promotion");

            $userManager = $this->m_managers->getManagerOf('user');
            $departments = $userManager->getDepartments();

            $packageManager = $this->m_managers->getManagerOf('package');
            $packages = $packageManager->get();

            $packageChoices = array();
            foreach($packages as $pack)
                $packageChoices[$pack->getId()] = $pack->getName('fr') . ' ' . $pack->getRegistrationsCount() . '/' . $pack->getCapacity();

            $this->page()->addVar("packageChoices", $packageChoices);
            $this->page()->addVar("departmentChoices", $departments);

            if($request->postExists('Envoyer'))
            {
                $department = $request->postData('department');
                $schoolYear = $request->postData('schoolYear');
                $idPackage = $request->postData('idPackage');

                // Check dpt, schoolYear and idPackage validity
                if( (!array_key_exists($department, $departments)) ||
                    (!in_array($schoolYear, array(3, 4, 5) )) ||
                    (!array_key_exists($idPackage, $packageChoices)) )
                {
                    $this->app()->user()->setFlashError('Departement, année d\'étude ou package inconnus');    
                    $this->app()->httpResponse()->redirect($request->requestURI());
                }

                $subscribe = 0;

                $registrationManager = $this->m_managers->getManagerOf('registration');
                $lectureManager = $this->m_managers->getManagerOf('lecture');

                $tmp = $packageManager->get($idPackage);
                $packageNeeded = $tmp[0];
                $packageResgistrationsCount = $packageNeeded->getRegistrationsCount();

                $students = $userManager->getFromDepartmentAndSchoolYear($department, intval($schoolYear) - 2);
                foreach($students as $student)
                {
                    $username = $student->getUsername();
                    $registrationsOfUser = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);

                    // The user cannot subscribe to more than the fixed number of packages
                    $packagesCount = $this->countSelectedPackages($registrationsOfUser);
                    if($packagesCount >= $this->m_managers->getManagerOf('config')->get('packageRegistrationsCount'))
                        continue;

                    // Don't register the student if he is already registered
                    if( $this->alreadyRegistered($idPackage, $registrationsOfUser) )
                        continue;

                    // Stop if there isn't no place in the package
                    if($packageResgistrationsCount + 1 > $packageNeeded->getCapacity())
                        break;

                    // Don't register the student if there is conflicts
                    if( !$this->checkConflict($registrationsOfUser, $packageNeeded) )
                        continue;
        
                    // Registration
                    $lectures = $lectureManager->get($idPackage);
                
                    foreach($lectures as $lecture)
                        $registrationManager->subscribe($idPackage, $lecture->getId(), $username, 1);

                    $packageResgistrationsCount++;
                    $subscribe++;
                }

                $this->app()->user()->setFlashInfo('Sur ' . count($students) . ' élèves de la promo (' . $department . ' ' . $schoolYear . '), ' . $subscribe . ' ont été inscrits au package demandé.');
                $this->app()->httpResponse()->redirect($request->requestURI());  
            }
        }





        private function checkConflict($registrationsOfUser, $packageNeeded)
        {
            $managerLecture = $this->m_managers->getManagerOf('lecture');

            $lectures = array();
            foreach($registrationsOfUser as $reg)
            {
                $lecturesOfRegistration = $managerLecture->get(-1, $reg->getIdLecture());

                foreach($lecturesOfRegistration as $l)
                    array_push($lectures, $l);
            }

            $lecturesOfPackageNeeded = $managerLecture->get($packageNeeded->getId());

            foreach($lecturesOfPackageNeeded as $l)
                array_push($lectures, $l);

            // Check all possible conflit
            for($i=0; $i<count($lectures); $i++)
            {
                for($j=($i+1); $j<count($lectures); $j++)
                {
                    if(Tools::conflict($lectures[$i], $lectures[$j]))
                        return false;
                }
            }

            return true;
        }

        private function countSelectedPackages($registrations)
        {
            $existingPackages = array();
            foreach($registrations as $reg)
            {
                if(!in_array($reg->getIdPackage(), $existingPackages))
                    $existingPackages[] = $reg->getIdPackage();
            }

            return count($existingPackages);
        }

        private function alreadyRegistered($idPackage, $registrations)
        {
            foreach($registrations as $reg)
            {
                if($idPackage == $reg->getIdPackage())
                    return true;
            }

            return false;
        }
    }
?>
