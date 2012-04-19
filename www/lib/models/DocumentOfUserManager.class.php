<?php
    class DocumentOfUserManager extends Manager
    {
        public function get($idPackage = -1, $idUser = -1)
        {
            $paramsSQL = array();

            $requestSQL = 'SELECT Id_package,
                                  Id_user,
                                  Filename FROM DocumentsOfUsers';

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
                $paramsSQL[] = $idPackage;
            }

            if($idUser != -1)
            {
                $connect = ($idPackage != -1) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' Id_user = ?';
                $paramsSQL[] = $idUser;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL); 

            $docs = array();

            while($data = $req->fetch())
            {
                $doc = new DocumentOfUser;
                $doc->setIdPackage($idPackage);
                $doc->setIdUser($idUser);
                $doc->setFilename($data['Filename']);

                $docs[] = $doc;
            }

            return $docs;
        }

        public function save(DocumentOfUser $doc)
        {
            $req = $this->m_dao->prepare('INSERT INTO DocumentsOfUsers(Id_package,
                                                                       Id_user, 
                                                                       Filename) VALUES(?, ?, ?)');

            $req->execute(array($doc->getIdPackage(),
                                $doc->getIdUser(),
                                $doc->getFilename()));
        }
    }
?>
