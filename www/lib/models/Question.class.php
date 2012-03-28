<?php
    class Question extends Record
    {
        private $m_idQuestion;
        private $m_idLecture;
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

        public function setIdLecture($idLecture)
        {
            $this->m_idLecture = $idLecture;
        }

        public function getIdLecture()
        {
            return $this->m_idLecture;
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
