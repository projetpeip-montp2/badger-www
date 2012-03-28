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
            $req = $this->m_dao->prepare('SELECT * FROM Users WHERE Id_user = ?');
            $req->execute(array($student->getUsername()));
            
            $data = $req->fetch();

            if(!$data)
            {
                $req = $this->m_dao->prepare('INSERT INTO Users(Id_user, HasTakenMCQ, Mark) VALUES(?, FALSE, 0)');
                $req->execute(array($student->getUsername()));
            }
            
            else
            {
                $student->setHasTakenMCQ($data['HasTakenMCQ']);
                $student->setMark($data['Mark']);
            }
        }
    }
?>
