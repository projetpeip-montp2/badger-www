<?php
    class ConnectionManager extends Manager
    {
        private function loadStudentFromPolytech($logon)
        {
            $db_polytech = new Database('localhost', 'vbMifare', 'vbMifare2012', 'Polytech');

            $reqPoly = $db_polytech->prepare('SELECT Username, Departement, anApogee, Mifare FROM Users WHERE Username = ?');
            $reqPoly->execute(array($logon));

            return new Student($reqPoly->fetch());
        }

        public function retrieveStudent($logon)
        {
            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $reqVB = $db_vbMifare->prepare('SELECT Id_user FROM Users WHERE Id_user = ?');
            $reqVB->execute(array($logon));
            
            if(!$reqVB->fetch())
            {
                $reqVB = $db_vbMifare->prepare('INSERT INTO Users(Id_user, Mark) VALUES(?, 0)');
                $reqVB->execute(array($logon));
            }

            return $this->loadStudentFromPolytech($logon);
        }
    }
?>
