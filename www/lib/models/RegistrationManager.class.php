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
                $reg->setIdUser($data['Id_user']);
                $reg->setStatus($data['Status']);

                $result[] = $reg;
            }

            return $result;
        }

        public function subscribe($idPackage, $username, $yesOrNo)
        {
            $req = $this->m_dao->prepare('SELECT Id_package FROM Registrations WHERE Id_package = ? AND Id_user = ?');
            $req->execute(array($idPackage, $username));

            $data = $req->fetch();

            if(!$data && $yesOrNo == 1)
            {
                $req = $this->m_dao->prepare('INSERT INTO Registrations(Id_package, Id_user, Status) VALUES(?, ?, "Coming")');
                $req->execute(array($idPackage, $username));
            }
            
            if($data && $yesOrNo == 0)
            {
                $req = $this->m_dao->prepare('DELETE FROM Registrations WHERE Id_package = ? AND Id_user = ?');
                $req->execute(array($idPackage, $username));
            }
        }
    }
?>
