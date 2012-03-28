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
                $reg->setId($data['Id_lecture']);
                $reg->setIdUser($data['Id_user']);
                $reg->setStatus($data['Status']);

                $result[] = $reg;
            }

            return $result;
        }

        public function getResgistrationsIdFromUser($idUsername)
        {
            $req = $this->m_dao->prepare('SELECT Id_lecture FROM Registrations WHERE Id_user = ?');
            $req->execute(array($idUsername));

            $result = array();
            
            while($data = $req->fetch())
                $result[] = $data['Id_lecture'];

            return $result;
        }

        public function subscribe($idLecture, $username, $yesOrNo)
        {
            $req = $this->m_dao->prepare('SELECT Id_lecture FROM Registrations WHERE Id_lecture = ? AND Id_user = ?');
            $req->execute(array($idLecture, $username));

            $data = $req->fetch();

            if(!$data && $yesOrNo == 1)
            {
                $req = $this->m_dao->prepare('INSERT INTO Registrations(Id_lecture, Id_user, Status) VALUES(?, ?, "Coming")');
                $req->execute(array($idLecture, $username));
            }
            
            if($data && $yesOrNo == 0)
            {
                $req = $this->m_dao->prepare('DELETE FROM Registrations WHERE Id_lecture = ? AND Id_user = ?');
                $req->execute(array($idLecture, $username));
            }
        }
    }
?>
