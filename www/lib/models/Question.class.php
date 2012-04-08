<?php
    class Question extends Record
    {
        private $m_idQuestion;
        private $m_idPackage;
        private $m_label_fr;
        private $m_label_en;
        private $m_status;

        public function setId($idQuestion)
        {
            $this->m_idQuestion = $idQuestion;
        }

        public function getId()
        {
            return $this->m_idQuestion;
        }

        public function setIdPackage($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getIdPackage()
        {
            return $this->m_idPackage;
        }

        public function setLabelFr($labelFr)
        {
            $this->m_label_fr = $labelFr;
        }

        public function getlabelFr()
        {
            return $this->m_label_fr;
        }

        public function setLabelEn($labelEn)
        {
            $this->m_label_en = $labelEn;
        }

        public function getlabelEn()
        {
            return $this->m_label_en;
        }

        public function setStatus($status)
        {
            $this->m_status = $status;
        }

        public function getStatus()
        {
            return $this->m_status;
        }
    }
?>
