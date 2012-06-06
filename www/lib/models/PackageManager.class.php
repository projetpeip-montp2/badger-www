<?php
    class PackageManager extends Manager
    {
        public function get($id = -1)
        {
            $requestSQL = 'SELECT Id_package,
                                  Capacity,
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
                $reqCount = $this->m_dao->prepare('SELECT COUNT(DISTINCT Id_package AND Id_user) FROM Registrations WHERE Id_package = ?');
                $reqCount->execute( array($data['Id_package']) ); 
                $registrationsCount = $reqCount->fetch();

                $package = new Package;
                $package->setId($data['Id_package']);
                $package->setCapacity($data['Capacity']);
                $package->setRegistrationsCount( $registrationsCount['COUNT(DISTINCT Id_package AND Id_user)'] );
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
                                                               Name_fr, 
                                                               Name_en,
                                                               Description_fr,
                                                               Description_en) VALUES(?, ?, ?, ?, ?)');

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
                                                              Name_fr = ?, 
                                                              Name_en = ?,
                                                              Description_fr = ?,
                                                              Description_en = ? WHERE Id_Package = ?');

            $req->execute(array($package->getCapacity(),
                                $package->getName('fr'),
                                $package->getName('en'),
                                $package->getDescription('fr'),
                                $package->getDescription('en'),
                                $package->getId()));
        }

        public function delete($packageIds)
        {
            $req = $this->m_dao->prepare('DELETE FROM Packages WHERE Id_package = ?');

            foreach($packageIds as $packageId)
                $req->execute(array($packageId));
        }
    }
?>
