<?php
    class AnswerManager extends Manager
    {
        public function get($idQuestion)
        {
            $SQLreq = 'SELECT Id_answer,
                              Id_question,
                              Label_fr,
                              Label_en,
                              TrueOrFalse FROM Answers WHERE Id_question = ?';
            $req = $this->m_dao->prepare($SQLreq);
            $req->execute(array($idQuestion));

            $result = array();
            while($data = $req->fetch())
            {
                $answer = new Answer;
                $answer->setId($data['Id_answer']);
                $answer->setIdQuestion($data['Id_question']);
                $answer->setLabel('fr', $data['Label_fr']);
                $answer->setLabel('en', $data['Label_en']);
                $answer->setTrueOrFalse($data['TrueOrFalse']);

                $result[] = $answer;
            }

            return $result;
        }

        public function save($answers)
        {
            $req = $this->m_dao->prepare('INSERT INTO Answers(Id_question,
                                                              Label_fr, 
                                                              Label_en, 
                                                              TrueOrFalse) VALUES(?, ?, ?, ?)');

            foreach($answers as $answer)
            {
                if(!in_array($answer->getTrueOrFalse(), array('T', 'F')))
                    throw new InvalidArgumentException('Invalid answers true or false in McqManager::saveAnswers');

                $req->execute(array($answer->getIdQuestion(),
                                    $answer->getLabel('fr'),
                                    $answer->getLabel('en'),
                                    $answer->getTrueOrFalse()));
            }
        }

        public function saveAnswersOfUser($answers)
        {
            $req = $this->m_dao->prepare('INSERT INTO AnswersOfUsers(Id_user,
                                                                     Id_question,
                                                                     Id_answer) VALUES(?, ?, ?)');
            foreach($answers as $answer)
                $req->execute(array($answer->getIdUser(),
                                    $answer->getIdQuestion(),
                                    $answer->getIdAnswer()));
        }
    }
?>
