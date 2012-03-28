<?php
    class RegistrationManager extends Manager
    {
        public function getResgistrationsFromUser($idUsername)
        {
            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $req = $db_vbMifare->prepare('SELECT Id_lecture FROM Registrations WHERE Id_user = ?');
            $req->execute(array($idUsername));

            $result = array();
            
            while($data = $req->fetch())
                $result[] = $data['Id_lecture'];

            return $result;
        }

        public function subscribe($idLecture, $username, $yesOrNo)
        {
            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $req = $db_vbMifare->prepare('SELECT Id_lecture FROM Registrations WHERE Id_lecture = ? AND Id_user = ?');
            $req->execute(array($idLecture, $username));

            $data = $req->fetch();

            if(!$data && $yesOrNo == 1)
            {
                $req = $db_vbMifare->prepare('INSERT INTO Registrations(Id_lecture, Id_user, Status) VALUES(?, ?, "Coming")');
                $req->execute(array($idLecture, $username));
            }
            
            if($data && $yesOrNo == 0)
            {
                $req = $db_vbMifare->prepare('DELETE FROM Registrations WHERE Id_lecture = ? AND Id_user = ?');
                $req->execute(array($idLecture, $username));
            }
        }
    }
?>
