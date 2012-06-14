<?php
    class Registration extends Record
    {
        private $m_idRegistration;
        private $m_idPackage;
        private $m_idLecture;
        private $m_idUser;
        private $m_status;

        public function setId($idRegistration)
        {
            $this->m_idRegistration = $idRegistration;
        }

        public function getId()
        {
            return $this->m_idRegistration;
        }

        public function setIdPackage($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getIdPackage()
        {
            return $this->m_idPackage;
        }

        public function setIdLecture($idLecture)
        {
            $this->m_idLecture = $idLecture;
        }

        public function getIdLecture()
        {
            return $this->m_idLecture;
        }

        public function setIdUser($idUser)
        {
            $this->m_idUser = $idUser;
        }

        public function getIdUser()
        {
            return $this->m_idUser;
        }

        public function setStatus($status)
        {
            $this->m_status = $status;
        }

        public function getStatus()
        {
            return $this->m_status;
        }
    }
?>
