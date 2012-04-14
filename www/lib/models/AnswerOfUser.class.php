<?php
    class AnswerOfUser extends Record
    {
        private $m_idUser;
        private $m_idQuestion;
        private $m_idAnswer;

        public function setIdUser($idUser)
        {
            $this->m_idUser = $idUser;
        }

        public function getIdUser()
        {
            return $this->m_idUser;
        }

        public function setIdQuestion($idQuestion)
        {
            $this->m_idQuestion = $idQuestion;
        }

        public function getIdQuestion()
        {
            return $this->m_idQuestion;
        }

        public function setIdAnswer($idAnswer)
        {
            $this->m_idAnswer = $idAnswer;
        }

        public function getIdAnswer()
        {
            return $this->m_idAnswer;
        }
    }
?>
