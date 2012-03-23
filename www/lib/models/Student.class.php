<?php
    class Student extends Record
    {
        private $m_username;
        private $m_departement; 
        private $m_year;
        private $m_mifare;

        private $m_mark;

        public function __construct($data)
        {
            $this->hydrate($data);
        }

        public function setUsername($username)
        {
            $this->m_username = $username;
        }

        public function setDepartement($departement)
        {
            $this->m_departement = $departement;
        }

        public function getDepartement()
        {
            return $this->m_departement;
        }

        public function setAnApogee($year)
        {
            $this->m_year = $year;
        }

        public function getAnApogee()
        {
            return $this->m_year;
        }

        public function setMifare($mifare)
        {
            $this->m_mifare = $mifare;
        }

        public function setMark($mark)
        {
            $this->m_mark = $mark;
        }
    }
?>
