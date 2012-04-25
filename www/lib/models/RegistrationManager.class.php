<?php
    class RegistrationManager extends Manager
    {
        public function getResgistrationsFromUser($idUsername, $status = NULL)
        {
            $SQLreq = 'SELECT * FROM Registrations WHERE Id_user = ?';
            $SQLparams = array($idUsername);
            if($status)
            {
                if(!in_array($status, array('Absent', 'Present', 'Coming')))
                    throw new InvalidArgumentException('Invalid status in RegistrationManager::getResgistrationsFromUser');

                $SQLreq .= ' AND Status = ?';
                $SQLparams[] = $status;
            }

            $req = $this->m_dao->prepare($SQLreq);
            $req->execute($SQLparams);

            $result = array();
            
            while($data = $req->fetch())
            {
                $reg = new Registration;
                $reg->setIdPackage($data['Id_package']);
                $reg->setIdLecture($data['Id_lecture']);
                $reg->setIdUser($data['Id_user']);
                $reg->setStatus($data['Status']);

                $result[] = $reg;
            }

            return $result;
        }

        public function delete($idLecture)
        {
            $req = $this->m_dao->prepare('DELETE FROM Registrations WHERE Id_lecture = ?');
            $req->execute(array($idLecture));
        }

        public function deleteFromUser($idUser)
        {
            $req = $this->m_dao->prepare('DELETE FROM Registrations WHERE Id_user = ?');
            $req->execute(array($idUser));
        }

        public function subscribe($idPackage, $idLecture, $username, $yesOrNo)
        {
            $req = $this->m_dao->prepare('SELECT Id_lecture FROM Registrations WHERE Id_lecture = ? AND Id_user = ?');
            $req->execute(array($idLecture, $username));

            $data = $req->fetch();

            if(!$data && $yesOrNo == 1)
            {
                $req = $this->m_dao->prepare('INSERT INTO Registrations(Id_package, Id_lecture, Id_user, Status) VALUES(?, ?, ?, "Coming")');
                $req->execute(array($idPackage, $idLecture, $username));
            }
            
            if($data && $yesOrNo == 0)
            {
                $req = $this->m_dao->prepare('DELETE FROM Registrations WHERE Id_lecture = ? AND Id_user = ?');
                $req->execute(array($idLecture, $username));
            }
        }
    }
?>
