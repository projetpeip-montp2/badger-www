<?php
    class PackageManager extends Manager
    {
        public function get($lang, $id = -1)
        {
            $methodName = 'setName'.ucfirst($lang);
            $methodDescription = 'setDescription'.ucfirst($lang);

            $requestSQL = 'SELECT Id_package,
                                  Name_'.$lang.' FROM Packages';

            if($id != -1)
                $requestSQL .= ' WHERE Id_package = ' . $id;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $packages = array();

            while($data = $req->fetch())
            {
                $package = new Lecture;
                $package->setId($data['Id_package']);
                $package->$methodName($data['Name_'.$lang]);

                $packages[] = $package;
            }

            return $packages;
        }

        public function save($packages)
        {
            $req = $this->m_dao->prepare('INSERT INTO Packages(Name_fr, 
                                                               Name_en) VALUES(?, ?)');

            foreach($packages as $package)
                $req->execute(array($package->getNameFr(),
                                    $package->getNameEn()));
        }
    }
?>
