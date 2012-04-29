<?php
    class PackageManager extends Manager
    {
        public function get($id = -1)
        {
            $requestSQL = 'SELECT Id_package,
                                  Capacity,
                                  RegistrationsCount,
                                  Name_fr, 
                                  Name_en,
                                  Description_fr,
                                  Description_en FROM Packages';

            if($id != -1)
                $requestSQL .= ' WHERE Id_package = ' . $id;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $packages = array();

            while($data = $req->fetch())
            {
                $package = new Package;
                $package->setId($data['Id_package']);
                $package->setCapacity($data['Capacity']);
                $package->setRegistrationsCount($data['RegistrationsCount']);
                $package->setName('fr', $data['Name_fr']);
                $package->setName('en', $data['Name_en']);
                $package->setDescription('fr', $data['Description_fr']);
                $package->setDescription('en', $data['Description_en']);

                $packages[] = $package;
            }

            return $packages;
        }

        public function save($packages)
        {
            $req = $this->m_dao->prepare('INSERT INTO Packages(Capacity,
                                                               RegistrationsCount,
                                                               Name_fr, 
                                                               Name_en,
                                                               Description_fr,
                                                               Description_en) VALUES(?, ?, ?, ?, ?, ?)');

            foreach($packages as $package)
                $req->execute(array($package->getCapacity(),
                                    $package->getRegistrationsCount(),
                                    $package->getName('fr'),
                                    $package->getName('en'),
                                    $package->getDescription('fr'),
                                    $package->getDescription('en')));
        }

        public function update($package)
        {
            $req = $this->m_dao->prepare('UPDATE Packages SET Capacity = ?,
                                                              RegistrationsCount = ?,
                                                              Name_fr = ?, 
                                                              Name_en = ?,
                                                              Description_fr = ?,
                                                              Description_en = ? WHERE Id_Package = ?');

            $req->execute(array($package->getCapacity(),
                                $package->getRegistrationsCount(),
                                $package->getName('fr'),
                                $package->getName('en'),
                                $package->getDescription('fr'),
                                $package->getDescription('en'),
                                $package->getId()));
        }

        public function updateRegistrationsCount($packageId, $newRegistrationsCount)
        {
            $req = $this->m_dao->prepare('UPDATE Packages SET RegistrationsCount = ? WHERE Id_Package = ?');

            $req->execute(array($newRegistrationsCount, $packageId));
        }

        public function delete($packageIds)
        {
            $req = $this->m_dao->prepare('DELETE FROM Packages WHERE Id_package = ?');

            foreach($packageIds as $packageId)
                $req->execute(array($packageId));
        }
    }
?>
