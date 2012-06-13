<?php
    class UserManager extends Manager
    {
        public function get()
        {
            $req = $this->m_dao->prepare('SELECT * FROM Users');
            $req->execute();

            $reqNext = $this->m_dao->prepare('SELECT VraiPrenom, VraiNom, Departement, anApogee, Num_Etudiant FROM UsersPolytech WHERE Username = ?');

            $students = array();
            while($data = $req->fetch())
            {
                $reqNext->execute(array($data['Id_user']));
                $dataNext = $reqNext->fetch();

                $student = new Student;
                $student->setUsername($data['Id_user']);
                $student->setMCQMark($data['MCQMark']);
                $student->setPresentMark($data['PresentMark']);
                $student->setMCQStatus($data['MCQStatus']);

                $student->setName($dataNext['VraiPrenom']);
                $student->setSurname($dataNext['VraiNom']);
                $student->setDepartment($dataNext['Departement']);
                $student->setSchoolYear($dataNext['anApogee']);
                $student->setStudentNumber($dataNext['Num_Etudiant']);

                $students[] = $student;
            }

            return $students;
        }

        public function getFromDepartmentAndSchoolYear($department, $schoolYear)
        {
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
            $req = $this->m_dao->prepare('SELECT Username, VraiPrenom, VraiNom, Departement, anApogee, Mifare, Actif FROM UsersPolytech WHERE Username = ?');
            $req->execute(array($logon));

            $data = $req->fetch();

            if(!$data)
                return NULL;

            $student = new Student;
            $student->setUsername($data['Username']);
            $student->setName($data['VraiPrenom']);
            $student->setSurname($data['VraiNom']);
            $student->setDepartment($data['Departement']);
            $student->setActive($data['Actif']);
            $student->setSchoolYear($data['anApogee']);
            $student->setMifare($data['Mifare']);

            return $student;
        }

        public function getMCQStatus($username)
        {
            $req = $this->m_dao->prepare('SELECT MCQStatus FROM Users WHERE Id_user = ?');
            $req->execute(array($username));

            $data = $req->fetch();

            if(!$data)
                throw new RuntimeException('Unkown user : ' . $username);            

            return $data['MCQStatus'];
        }

        public function insertOrLoadIfFirstVisit(Student $student, $status)
        {
            $this->checkStatus($status);

            $req = $this->m_dao->prepare('SELECT * FROM Users WHERE Id_user = ?');
            $req->execute(array($student->getUsername()));
            
            $data = $req->fetch();

            if(!$data)
            {
                $req = $this->m_dao->prepare('INSERT INTO Users(Id_user, MCQStatus, MCQMark, PresentMark) VALUES(?, ?, 0, 0)');
                $req->execute(array($student->getUsername(), $status));
                $student->setMCQStatus($status);
                $student->setMCQMark(0);
                $student->setPresentMark(0);
            }
            
            else
            {
                $student->setMCQStatus($data['MCQStatus']);
                $student->setMCQMark($data['MCQMark']);
                $student->setPresentMark($data['PresentMark']);
            }
        }

        public function updateStatus($logon, $status)
        {
            $this->checkStatus($status);

            $req = $this->m_dao->prepare('UPDATE Users SET MCQStatus = ? WHERE Id_user = ?');
            $req->execute(array($status, $logon));
        }

        public function updateMarks($logon, $mcqMark, $presentMark)
        {
            $req = $this->m_dao->prepare('UPDATE Users SET MCQMark = ?, PresentMark = ? WHERE Id_user = ?');
            $req->execute(array($mcqMark, $presentMark, $logon));
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

        public function isSpecificLogon($logon)
        {
            $req = $this->m_dao->prepare('SELECT Username FROM SpecificLogins WHERE UsernameUM2 = ?');
            $req->execute(array($logon));
            $result = $req->fetch();

            if($result)
                return $result['Username'];

            return FALSE;
        }

        public function getSpecificLogins()
        {
            $req = $this->m_dao->prepare('SELECT * FROM SpecificLogins');
            $req->execute();
            return $req->fetchAll();
        }
    }
?>
