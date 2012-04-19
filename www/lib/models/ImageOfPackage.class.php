<?php
    class ImageOfPackage extends Record
    {
        private $m_idImage;
        private $m_idPackage;
        private $m_filename;

        public function setId($idImage)
        {
            $this->m_idImage = $idImage;
        }

        public function getId()
        {
            return $this->m_idImage;
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
