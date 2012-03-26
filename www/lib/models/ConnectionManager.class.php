<?php
    class ConnectionManager extends Manager
    {
        public function retrieveStudentFromPolytech($logon)
        {
            $db_polytech = new Database('localhost', 'vbMifare', 'vbMifare2012', 'Polytech');

            $req = $db_polytech->prepare('SELECT Username, Departement, anApogee, Mifare, Actif, Statut FROM Users WHERE Username = ?');
            $req->execute(array($logon));

            $data = $req->fetch();

            $student = new Student;
            $student->setUsername($data['Username']);
            $student->setDepartement($data['Departement']);
            $student->setActive($data['Actif']);
            $student->setStatus($data['Statut']);
            $student->setSchoolYear($data['anApogee']);
            $student->setMifare($data['Mifare']);

            return $student;
        }

        public function insertOrLoadIfFirstVisit(Student $student)
        {
            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $req = $db_vbMifare->prepare('SELECT * FROM Users WHERE Id_user = ?');
            $req->execute(array($student->getUsername()));
            
            $data = $req->fetch();

            if(!$data)
            {
                $req = $db_vbMifare->prepare('INSERT INTO Users(Id_user, HasPassedMCQ, Mark) VALUES(?, FALSE, 0)');
                $req->execute(array($student->getUsername()));
            }
            
            else
            {
                $student->setHasPassedMCQ($data['HasPassedMCQ']);
                $student->setMark($data['Mark']);
            }
        }
    }
?>
