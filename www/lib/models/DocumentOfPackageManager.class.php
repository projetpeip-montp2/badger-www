<?php
    class DocumentOfPackageManager extends Manager
    {
        public function get($idPackage = -1)
        {
            $requestSQL = 'SELECT Id_package,
                                  Filename,
                                  Path,
                                  Downloadable FROM DocumentsOfPackages';

            if($idPackage != -1)
                $requestSQL .= ' WHERE Id_package = ' . $idPackage;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $documents = array();

            while($data = $req->fetch())
            {
                $document = new DocumentOfPackage;
                $document->setIdPackage($data['Id_package']);
                $document->setFilename($data['Filename']);
                $document->setPath($data['Path']);
                $document->setDownloadable($data['Downloadable']);

                $documents[] = $document;
            }

            return $documents;
        }

        public function save($document)
        {
            $req = $this->m_dao->prepare('INSERT INTO DocumentsOfPackages(Id_package,
                                                                         Filename, 
                                                                         Path, 
                                                                         Downloadable) VALUES(?, ?, ?, ?)');

            $req->execute(array($document->getIdPackage(),
                                $document->getFilename(),
                                $document->getPath(),
                                $document->getDownloadable()));
        }

// TODO: DELETE à réfléchir
        public function delete($documentId)
        {
            $req = $this->m_dao->prepare('DELETE FROM Lectures WHERE Id_lecture = ?');

            foreach($lectureIds as $lectureId)
                $req->execute(array($lectureId));
        }
    } 
?>
