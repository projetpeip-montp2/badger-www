<?php
    class ArchiveOfPackageManager extends Manager
    {
        public function get($idPackage = -1, $idImage = -1)
        {
            $requestSQL = 'SELECT Id_archive,
                                  Id_package,
                                  Filename FROM ArchivesOfPackages';

            $paramsSQL = array();

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
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

            $archives = array();

            while($data = $req->fetch())
            {
                $archive = new ArchiveOfPackage;
                $archive->setId($data['Id_archive']);
                $archive->setIdPackage($data['Id_package']);
                $archive->setFilename($data['Filename']);

                $archives[] = $archive;
            }

            return $archives;
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

        public function count($idPackage = -1)
        {
            $requestSQL = 'SELECT COUNT(*) FROM ArchivesOfPackages';

            $paramsSQL = array();

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
                $paramsSQL[] = $idPackage;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL);

            $count = $req->fetch();
            return $count['COUNT(*)'];
        }

        public function lastInsertId()
        {
            return $this->m_dao->lastInsertId();
        }
    }
?>
