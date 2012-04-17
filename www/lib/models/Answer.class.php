<?php
    class Answer extends Record
    {
        private $m_idAnswer;
        private $m_idQuestion;
        private $m_label;
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

        public function setLabel($lang, $label)
        {
            $this->m_label[$lang] = $label;
        }

        public function getLabel($lang)
        {
            return $this->m_label[$lang];
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
