<?php
    class DocumentOfPackage extends Record
    {
        private $m_idDocument;
        private $m_idPackage;
        private $m_filename;

        public function setId($idDocument)
        {
            $this->m_idDocument = $idDocument;
        }

        public function getId()
        {
            return $this->m_idDocument;
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
