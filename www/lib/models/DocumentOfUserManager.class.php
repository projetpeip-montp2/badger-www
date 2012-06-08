<?php
    class DocumentOfUserManager extends Manager
    {
        public function get($idPackage = -1, $idUser = null)
        {
            $requestSQL = 'SELECT Id_document,
                                  Id_package,
                                  Id_user,
                                  Filename FROM DocumentsOfUsers';

            $paramsSQL = array();

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
                $paramsSQL[] = $idPackage;
            }

            if($idUser != null)
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
                $doc->setId($data['Id_document']);
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

        public function delete($idPackage, $idUser)
        {
            $req = $this->m_dao->prepare('DELETE FROM DocumentsOfUsers WHERE Id_package = ? AND Id_user = ?');
            var_dump($idPackage);
            var_dump($idUser); die();

            $req->execute(array($idPackage, $idUser));
        }
    }
?>
