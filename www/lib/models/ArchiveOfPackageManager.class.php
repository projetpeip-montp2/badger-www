<?php
    class ArchiveOfPackageManager extends Manager
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

        public function save($archive)
        {
            $req = $this->m_dao->prepare('INSERT INTO ArchivesOfPackages (Id_package,
                                                                         Filename) VALUES(?, ?)');

            $req->execute(array($archive->getIdPackage(),
                                $archive->getFilename()));
        }

        public function delete($idArchive)
        {
            $req = $this->m_dao->prepare('DELETE FROM ArchivesOfPackages WHERE Id_archive = ?');

            $req->execute(array($idArchive));
        }

        public function lastInsertId()
        {
            return $this->m_dao->lastInsertId();
        }
    }
?>
