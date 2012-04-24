<?php
    class Package extends Record
    {
        private $m_idPackage;
        private $m_capacity;
        private $m_registrationsCount;
        private $m_name;
        private $m_description;

        public function setId($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getId()
        {
            return $this->m_idPackage;
        }

        public function setCapacity($capacity)
        {
            $this->m_capacity = $capacity;
        }

        public function getCapacity()
        {
            return $this->m_capacity;
        }

        public function setRegistrationsCount($registrationsCount)
        {
            $this->m_registrationsCount = $registrationsCount;
        }

        public function getRegistrationsCount()
        {
            return $this->m_registrationsCount;
        }

        public function setName($lang, $name)
        {
            $this->m_name[$lang] = $name;
        }

        public function getName($lang)
        {
            return $this->m_name[$lang];
        }

        public function setDescription($lang, $description)
        {
            $this->m_description[$lang] = $description;
        }

        public function getDescription($lang)
        {
            return $this->m_description[$lang];
        }
    }
?>
