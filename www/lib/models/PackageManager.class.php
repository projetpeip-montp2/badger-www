<?php
    class PackageManager extends Manager
    {
        public function get($lang, $id = -1)
        {
            $methodName = 'setName'.ucfirst($lang);
            $methodDescription = 'setDescription'.ucfirst($lang);

            $requestSQL = 'SELECT Id_package,
                                  Lecturer,
                                  Name_'.$lang.',
                                  Description_'.$lang.' FROM Packages';

            if($id != -1)
                $requestSQL .= ' WHERE Id_package = ' . $id;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $packages = array();

            while($data = $req->fetch())
            {
                $package = new Package;
                $package->setId($data['Id_package']);
                $package->setLecturer($data['Lecturer']);
                $package->$methodName($data['Name_'.$lang]);
                $package->$methodDescription($data['Description_'.$lang]);

                $packages[] = $package;
            }

            return $packages;
        }

        public function save($packages)
        {
            $req = $this->m_dao->prepare('INSERT INTO Packages(Lecturer,
                                                               Name_fr, 
                                                               Name_en,
                                                               Description_fr,
                                                               Description_en) VALUES(?, ?, ?, ?, ?)');

            foreach($packages as $package)
                $req->execute(array($package->getLecturer(),
                                    $package->getNameFr(),
                                    $package->getNameEn(),
                                    $package->getDescriptionFr(),
                                    $package->getDescriptionEn()));
        }
    }
?>
