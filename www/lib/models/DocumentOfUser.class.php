<?php
    class DocumentOfUser extends Record
    {
        private $m_idDocument;
        private $m_idLecture;
        private $m_idUser;
        private $m_idRegistration;
        private $m_filename;

        public function setId($idDocument)
        {
            $this->m_idDocument = $idDocument;
        }

        public function getId()
        {
            return $this->m_idDocument;
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

        public function setIdRegistration($idRegistration)
        {
            $this->m_idRegistration = $idRegistration;
        }

        public function getIdRegistration()
        {
            return $this->m_idRegistration;
        }

        public function setFilename($filename)
        {
            $this->m_filename = $filename;
        }

        public function getFilename()
        {
            return $this->m_filename;
        }
    }
?>
