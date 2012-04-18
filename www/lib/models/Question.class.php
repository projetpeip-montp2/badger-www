<?php
    class Question extends Record
    {
        private $m_idQuestion;
        private $m_idPackage;
        private $m_label;
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

        public function setLabel($lang, $label)
        {
            $this->m_label[$lang] = $label;
        }

        public function getLabel($lang)
        {
            return $this->m_label[$lang];
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
