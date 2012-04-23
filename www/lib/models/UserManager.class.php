<?php
    class UserManager extends Manager
    {
        private function checkStatus($status)
        {
            if(!in_array($status, array('Visitor', 'CanTakeMCQ', 'Generated', 'Taken')))
                throw new InvalidArgumentException('Invalid MCQStatus in ConnectionManager::insertOrLoadIfFirstVisit');
        }

        public function retrieveStudentFromPolytech($logon)
        {
            // The request is on Polytech database here!
            $req = $this->m_dao->prepare('SELECT Username, Departement, anApogee, Mifare, Actif, Statut FROM Polytech.Users WHERE Username = ?');
            $req->execute(array($logon));

            $data = $req->fetch();

            $student = new Student;
            $student->setUsername($data['Username']);
            $student->setDepartment($data['Departement']);
            $student->setActive($data['Actif']);
            $student->setStatus($data['Statut']);
            $student->setSchoolYear($data['anApogee']);
            $student->setMifare($data['Mifare']);

            return $student;
        }

        public function insertOrLoadIfFirstVisit(Student $student, $status)
        {
            $this->checkStatus($status);

            $req = $this->m_dao->prepare('SELECT * FROM Users WHERE Id_user = ?');
            $req->execute(array($student->getUsername()));
            
            $data = $req->fetch();

            if(!$data)
            {
                $req = $this->m_dao->prepare('INSERT INTO Users(Id_user, MCQStatus, Mark) VALUES(?, ?, 0)');
                $req->execute(array($student->getUsername(), $status));
                $student->setMCQStatus($status);
                $student->setMark(0);
            }
            
            else
            {
                $student->setMCQStatus($data['MCQStatus']);
                $student->setMark($data['Mark']);
            }
        }

        public function updateStatus($logon, $status)
        {
            $this->checkStatus($status);

            $req = $this->m_dao->prepare('UPDATE Users SET MCQStatus = ? WHERE Id_user = ?');
            $req->execute(array($status, $logon));
        }

        public function updateMark($logon, $mark)
        {
            $req = $this->m_dao->prepare('UPDATE Users SET Mark = ? WHERE Id_user = ?');
            $req->execute(array($mark, $logon));
        }

        public function getDepartments()
        {
            $req = $this->m_dao->query('SHOW COLUMNS FROM Polytech.Users LIKE \'Departement\'');

            $result = $req->fetch();

            // Remove enum( at the begining and ) at the end
            $tmp = $type = substr($result['Type'], 5, -1);

            $indexArray = str_getcsv($tmp, ',', '\'');

            $assocArray = array();
            foreach($indexArray as $element)
                $assocArray[$element] = $element;

            return $assocArray;
        }
    }
?>
