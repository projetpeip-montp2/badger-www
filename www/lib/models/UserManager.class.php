<?php
    class UserManager extends Manager
    {
        public function retrieveStudentFromPolytech($logon)
        {
            // The request is on Polytech database here!
            $req = $this->m_dao->prepare('SELECT Username, Departement, anApogee, Mifare, Actif, Statut FROM Polytech.Users WHERE Username = ?');
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

        public function insertOrLoadIfFirstVisit(Student $student, $mcqStatus)
        {
            if(!in_array($mcqStatus, array('Visitor', 'CanTakeMCQ', 'Generated', 'Taken')))
                throw new InvalidArgumentException('Invalid MCQStatus in ConnectionManager::insertOrLoadIfFirstVisit');

            $req = $this->m_dao->prepare('SELECT * FROM Users WHERE Id_user = ?');
            $req->execute(array($student->getUsername()));
            
            $data = $req->fetch();

            if(!$data)
            {
                $req = $this->m_dao->prepare('INSERT INTO Users(Id_user, MCQStatus, Mark) VALUES(?, ?, 0)');
                $req->execute(array($student->getUsername(), $mcqStatus));
                $student->setMCQStatus($mcqStatus);
                $student->setMark(0);
            }
            
            else
            {
                $student->setMCQStatus($data['MCQStatus']);
                $student->setMark($data['Mark']);
            }
        }
    }
?>
