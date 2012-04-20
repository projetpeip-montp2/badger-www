<?php
    class DocumentOfPackageManager extends Manager
    {
        public function get($idPackage = -1, $idDocument = -1)
        {
            $requestSQL = 'SELECT Id_document,
                                  Id_package,
                                  Filename FROM DocumentsOfPackages';

            $paramsSQL = array();

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
                $paramsSQL[] = $idPackage;
            }

            if($idDocument != -1)
            {
                $connect = ($idPackage != -1) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' Id_document = ?';
                $paramsSQL[] = $idDocument;
            }

            $req = $this->m_dao->prepare($requestSQL);

            $req->execute($paramsSQL);

            $documents = array();

            while($data = $req->fetch())
            {
                $document = new DocumentOfPackage;
                $document->setId($data['Id_document']);
                $document->setIdPackage($data['Id_package']);
                $document->setFilename($data['Filename']);

                $documents[] = $document;
            }

            return $documents;
        }

        public function save($document)
        {
            $req = $this->m_dao->prepare('INSERT INTO DocumentsOfPackages(Id_package,
                                                                         Filename) VALUES(?, ?)');

            $req->execute(array($document->getIdPackage(),
                                $document->getFilename(),));
        }

        public function delete($documentId)
        {
            $req = $this->m_dao->prepare('DELETE FROM DocumentsOfPackages WHERE Id_document = ?');

            $req->execute(array($documentId));
        }

        public function count($idPackage = -1)
        {
            $requestSQL = 'SELECT COUNT(*) FROM DocumentsOfPackages';

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
    } 
?>
