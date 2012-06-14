<?php
    class DocumentOfUserManager extends Manager
    {
        public function get($idLecture = -1, $idUser = null)
        {
            $requestSQL = 'SELECT Id_document,
                                  Id_lecture,
                                  Id_user,
                                  Id_registration,
                                  Filename FROM DocumentsOfUsers';

            $paramsSQL = array();

            if($idLecture != -1)
            {
                $requestSQL .= ' WHERE Id_lecture = ?';
                $paramsSQL[] = $idLecture;
            }

            if($idUser != null)
            {
                $connect = ($idLecture != -1) ? 'AND' : 'WHERE';
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
                $doc->setIdLecture($data['Id_lecture']);
                $doc->setIdUser($data['Id_user']);
                $doc->setIdRegistration($data['Id_registration']);
                $doc->setFilename($data['Filename']);

                $docs[] = $doc;
            }

            return $docs;
        }

        public function save(DocumentOfUser $doc)
        {
            $req = $this->m_dao->prepare('INSERT INTO DocumentsOfUsers(Id_lecture,
                                                                       Id_user, 
                                                                       Id_registration, 
                                                                       Filename) VALUES(?, ?, ?, ?)');

            $req->execute(array($doc->getIdLecture(),
                                $doc->getIdUser(),
                                $doc->getIdRegistration(),
                                $doc->getFilename()));
        }
    }
?>
