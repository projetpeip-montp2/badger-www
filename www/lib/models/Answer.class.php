<?php
    class Answer extends Record
    {
        private $m_idAnswer;
        private $m_idQuestion;
        private $m_label_fr;
        private $m_label_en;
        private $m_trueOrFalse;

        public function setId($idAnswer)
        {
            $this->m_idAnswer = $idAnswer;
        }

        public function getId()
        {
            return $this->m_idAnswer;
        }

        public function setIdQuestion($idQuestion)
        {
            $this->m_idQuestion = $idQuestion;
        }

        public function getIdQuestion()
        {
            return $this->m_idQuestion;
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

        public function setTrueOrFalse($trueOrFalse)
        {
            $this->m_trueOrFalse = $trueOrFalse;
        }

        public function getTrueOrFalse()
        {
            return $this->m_trueOrFalse;
        }
    }
?>
