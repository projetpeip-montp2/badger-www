<?php
    class ImageOfArchiveManager extends Manager
    {
        public function get($idArchive = -1, $idImage = -1)
        {
            $requestSQL = 'SELECT Id_image,
                                  Id_archive,
                                  Filename FROM ImagesOfArchives';

            $paramsSQL = array();

            if($idArchive != -1)
            {
                $requestSQL .= ' WHERE Id_archive = ?';
                $paramsSQL[] = $idPackage;
            }

            if($idImage != -1)
            {
                $connect = ($idArchive != -1) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' Id_image = ?';
                $paramsSQL[] = $idImage;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL);

            $images = array();

            while($data = $req->fetch())
            {
                $image = new ImageOfArchive;
                $image->setId($data['Id_image']);
                $image->setIdArchive($data['Id_archive']);
                $image->setFilename($data['Filename']);

                $images[] = $image;
            }

            return $images;
        }

        public function save($image)
        {
            $req = $this->m_dao->prepare('INSERT INTO ImagesOfArchives(Id_archive,
                                                                         Filename) VALUES(?, ?)');

            $req->execute(array($image->getIdArchive(),
                                $image->getFilename()));
        }

        public function delete($idArchive)
        {
            $req = $this->m_dao->prepare('DELETE FROM ImagesOfArchives WHERE Id_archive = ?');

            $req->execute(array($idArchive));
        }

        public function count($idArchive = -1)
        {
            $requestSQL = 'SELECT COUNT(*) FROM ImagesOfArchives';

            $paramsSQL = array();

            if($idArchive != -1)
            {
                $requestSQL .= ' WHERE Id_archive = ?';
                $paramsSQL[] = $idArchive;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL);

            $count = $req->fetch();
            return $count['COUNT(*)'];
        }
    } 
?>
