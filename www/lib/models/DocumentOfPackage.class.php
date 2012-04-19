<?php
    class DocumentOfPackage extends Record
    {
        private $m_idPackage;
        private $m_filename;
        private $m_path;
        private $m_downloadable;

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

        public function setPath($path)
        {
            $this->m_path = $path;
        }

        public function getPath()
        {
            return $this->m_path;
        }

        public function setDownloadable($downloadable)
        {
            $this->m_downloadable = $downloadable;
        }

        public function getDownloadable()
        {
            return $this->m_downloadable;
        }
    }
?>
