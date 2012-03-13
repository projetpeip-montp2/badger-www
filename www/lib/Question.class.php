<?php
    class Question
    {
        private $m_id;
        private $m_label;
        private $m_answers;

        public function __construct($id , $label , $answers)
        {
            $this->m_id = $id;
            $this->m_label = $label;
            $this->m_answers = $answers;
        }

        public function getId()
        {
            return $this->m_id;
        }

        public function getLabel()
        {
            return $this->m_label;
        }

        public function getAnswers()
        {
            return $this->m_answers;
        }
    }
?>
