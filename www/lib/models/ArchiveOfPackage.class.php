<?php
    class ArchiveOfPackage extends Record
    {
        private $m_idArchive;
        private $m_idPackage;
        private $m_filename;

        public function setId($idArchive)
        {
            $this->m_idArchive = $idArchive;
        }

        public function getId()
        {
            return $this->m_idArchive;
        }

        public function setIdPackage($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getIdPackage()
        {
            return $this->m_idPackage;
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
