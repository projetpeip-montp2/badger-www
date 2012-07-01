<?php
    class Student extends Record
    {
        private $m_username;
        private $m_name;
        private $m_surname;
        private $m_departement; 
        private $m_active;
        private $m_schoolYear;
        private $m_mifare;
        private $m_studentNumber;

        private $m_mcqStatus;
        private $m_mcqMark;
        private $m_presentMark;
        private $m_generateTime;

        public function setUsername($username)
        {
            $this->m_username = $username;
        }

        public function getUsername()
        {
            return $this->m_username;
        }

        public function setName($name)
        {
            $this->m_name = $name;
        }

        public function getName()
        {
            return $this->m_name;
        }

        public function setSurname($surname)
        {
            $this->m_surname = $surname;
        }

        public function getSurname()
        {
            return $this->m_surname;
        }

        public function setDepartment($department)
        {
            $this->m_department = $department;
        }

        public function getDepartment()
        {
            return $this->m_department;
        }

        public function setActive($active)
        {
            $this->m_active = $active;
        }

        public function getActive()
        {
            return $this->m_active;
        }

        public function setSchoolYear($schoolYear)
        {
            $this->m_schoolYear = $schoolYear;
        }

        public function getSchoolYear()
        {
            return $this->m_schoolYear;
        }

        public function setMifare($mifare)
        {
            $this->m_mifare = $mifare;
        }

        public function getMifare()
        {
            return $this->m_mifare;
        }

        public function setStudentNumber($studentNumber)
        {
            $this->m_studentNumber = $studentNumber;
        }

        public function getStudentNumber()
        {
            return $this->m_studentNumber;
        }

        public function setMCQMark($mcqMark)
        {
            $this->m_mcqMark = $mcqMark;
        }

        public function getMCQMark()
        {
            return $this->m_mcqMark;
        }

        public function setPresentMark($presentMark)
        {
            $this->m_presentMark = $presentMark;
        }

        public function getPresentMark()
        {
            return $this->m_presentMark;
        }

        public function setMCQStatus($mcqStatus)
        {
            $this->m_mcqStatus = $mcqStatus;
        }

        public function getMCQStatus()
        {
            return $this->m_mcqStatus;
        }

        public function setGenerateTime(Time $generateTime)
        {
            $this->m_generateTime = $generateTime;
        }

        public function getGenerateTime()
        {
            return $this->m_generateTime;
        }
    }
?>
