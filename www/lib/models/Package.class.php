<?php
    class Package extends Record
    {
        private $m_idPackage;
        private $m_name_fr;
        private $m_name_en;

        public function setId($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getId()
        {
            return $this->m_idPackage;
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
    }
?>
