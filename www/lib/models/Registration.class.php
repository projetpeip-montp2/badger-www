<?php
    class Registration extends Record
    {
        private $m_idPackage;
        private $m_idUser;
        private $m_status;

        public function setIdPackage($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getIdPackage()
        {
            return $this->m_idPackage;
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
