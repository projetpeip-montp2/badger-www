<?php
    class ImageOfPackageManager extends Manager
    {
        public function get($idPackage = -1, $idImage = -1)
        {
            $requestSQL = 'SELECT Id_image,
                                  Id_package,
                                  Filename FROM ImagesOfPackages';

            $paramsSQL = array();

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
                $paramsSQL[] = $idPackage;
            }

            if($idImage != -1)
            {
                $connect = ($idPackage != -1) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' Id_image = ?';
                $paramsSQL[] = $idImage;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL);

            $images = array();

            while($data = $req->fetch())
            {
                $image = new ImageOfPackage;
                $image->setId($data['Id_image']);
                $image->setIdPackage($data['Id_package']);
                $image->setFilename($data['Filename']);

                $images[] = $image;
            }

            return $images;
        }

        public function save($image)
        {
            $req = $this->m_dao->prepare('INSERT INTO ImagesOfPackages(Id_package,
                                                                         Filename) VALUES(?, ?)');

            $req->execute(array($image->getIdPackage(),
                                $image->getFilename()));
        }

        public function delete($imageIds)
        {
            $req = $this->m_dao->prepare('DELETE FROM ImagesOfPackages WHERE Id_image = ?');

            foreach($imageIds as $imageId)
                $req->execute(array($imageId));
        }
    } 
?>
