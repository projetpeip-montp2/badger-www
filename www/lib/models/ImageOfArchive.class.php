<?php
    class ImageOfArchive extends Record
    {
        private $m_idImage;
        private $m_idArchive;
        private $m_filename;

        public function setId($idImage)
        {
            $this->m_idImage = $idImage;
        }

        public function getId()
        {
            return $this->m_idImage;
        }

        public function setIdArchive($idArchive)
        {
            $this->m_idArchive = $idArchive;
        }

        public function getIdArchive()
        {
            return $this->m_idArchive;
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
