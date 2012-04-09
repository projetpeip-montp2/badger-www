<?php
    class Package extends Record
    {
        private $m_idPackage;
        private $m_lecturer;
        private $m_name_fr;
        private $m_name_en;
        private $m_description_fr;
        private $m_description_en;

        public function setId($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getId()
        {
            return $this->m_idPackage;
        }

        public function setLecturer($lecturer)
        {
            $this->m_lecturer = $lecturer;
        }

        public function getLecturer()
        {
            return $this->m_lecturer;
        }

        public function setNameFr($name_fr)
        {
            $this->m_name_fr = $name_fr;
        }

        public function getNameFr()
        {
            return $this->m_name_fr;
        }

        public function setNameEn($name_en)
        {
            $this->m_name_en = $name_en;
        }

        public function getNameEn()
        {
            return $this->m_name_en;
        }

        public function setDescriptionFr($description_fr)
        {
            $this->m_description_fr = $description_fr;
        }

        public function getDescriptionFr()
        {
            return $this->m_description_fr;
        }

        public function setDescriptionEn($description_en)
        {
            $this->m_description_en = $description_en;
        }

        public function getDescriptionEn()
        {
            return $this->m_description_en;
        }
    }
?>
