<?php
    class DocumentOfUserManager extends Manager
    {
        public function get($idLecture = -1, $idUser = null)
        {
            $requestSQL = 'SELECT Id_document,
                                  Id_lecture,
                                  Id_user,
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
                $doc->setIdLecture($idLecture);
                $doc->setIdUser($idUser);
                $doc->setFilename($data['Filename']);

                $docs[] = $doc;
            }

            return $docs;
        }

        public function save(DocumentOfUser $doc)
        {
            $req = $this->m_dao->prepare('INSERT INTO DocumentsOfUsers(Id_lecture,
                                                                       Id_user, 
                                                                       Filename) VALUES(?, ?, ?)');

            $req->execute(array($doc->getIdLecture(),
                                $doc->getIdUser(),
                                $doc->getFilename()));
        }

        public function delete($idLecture, $idUser)
        {
            $req = $this->m_dao->prepare('DELETE FROM DocumentsOfUsers WHERE Id_lecture = ? AND Id_user = ?');
            $req->execute(array($idLecture, $idUser));
        }
    }
?>
