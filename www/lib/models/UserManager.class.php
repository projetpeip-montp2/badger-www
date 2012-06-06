<?php
    class UserManager extends Manager
    {
        public function getAllFromvbMifare()
        {
            $req = $this->m_dao->prepare('SELECT * FROM Users');
            $req->execute();

            $reqNext = $this->m_dao->prepare('SELECT Departement, anApogee, Num_Etudiant FROM UsersPolytech WHERE Username = ?');

            $students = array();
            while($data = $req->fetch())
            {
                $reqNext->execute(array($data['Id_user']));
                $dataNext = $reqNext->fetch();

                $student = new Student;
                $student->setUsername($data['Id_user']);
                $student->setMark($data['Mark']);
                $student->setMCQStatus($data['MCQStatus']);
                $student->setDepartment($dataNext['Departement']);
                $student->setSchoolYear($dataNext['anApogee']);
                $student->setStudentNumber($dataNext['Num_Etudiant']);

                $students[] = $student;
            }

            return $students;
        }

        public function getFromDepartmentAndSchoolYear($department, $schoolYear)
        {
            // The request is on Polytech database here!
            $req = $this->m_dao->prepare('SELECT Username FROM UsersPolytech WHERE Departement = ? AND anApogee = ?');
            $req->execute(array($department, $schoolYear));

            $students = array();
            while($data = $req->fetch())
            {
                $student = new Student;
                $student->setUsername($data['Username']);

                $students[] = $student;
            }

            return $students;
        }

        private function checkStatus($status)
        {
            if(!in_array($status, array('Visitor', 'CanTakeMCQ', 'Generated', 'Taken')))
                throw new InvalidArgumentException('Invalid MCQStatus in ConnectionManager::insertOrLoadIfFirstVisit');
        }

        public function retrieveStudentFromPolytech($logon)
        {
            $req = $this->m_dao->prepare('SELECT Username, Departement, anApogee, Mifare, Actif, Statut FROM UsersPolytech WHERE Username = ?');
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

        public function retrieveMifare($logon)
        {
            $req = $this->m_dao->prepare('SELECT Mifare FROM UsersPolytech WHERE Username = ?');
            $req->execute(array($logon));

            $mifares = array();
            while($data = $req->fetch())
                $mifares[] = $data['Mifare'];

            return $mifares;
        }

        public function isInDatabase($logon)
        {
            $req = $this->m_dao->prepare('SELECT Id_User FROM Users WHERE Id_user = ?');
            $req->execute(array($logon));

            $data = $req->fetch();

            // TODO: Greg quand tu liras ça, factorise le return en return $data; non? :D
            if(!$data)
                return false;
            else
                return true;
        }

        public function getDepartments()
        {
            $req = $this->m_dao->query('SHOW COLUMNS FROM UsersPolytech LIKE \'Departement\'');

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
