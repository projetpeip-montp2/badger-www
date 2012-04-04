<?php
    class McqManager extends Manager
    {
        public function getQuestionsFromLecture($idLecture, $lang, $status = NULL)
        {
            $methodLabel = 'setLabel'.ucfirst($lang);

            $SQLreq = 'SELECT Id_question,
                              Label_'.$lang.',
                              Status FROM Questions WHERE Id_Lecture = ?';
            $SQLparams = array($idLecture);
            if($status)
            {
                if(!in_array($status, array('Impossible', 'Possible', 'Obligatory')))
                    throw new InvalidArgumentException('Invalid status in QuestionManager::getQuestionsFromLecture');

                $SQLreq .= ' AND Status = ?';
                $SQLparams[] = $status;
            }

            $req = $this->m_dao->prepare($SQLreq);
            $req->execute($SQLparams);

            $result = array();
            while($data = $req->fetch())
            {
                $question = new Question;
                $question->setId($data['Id_question']);
                $question->$methodLabel($data['Label_'.$lang]);
                $question->setStatus($data['Status']);

                $result[] = $question;
            }

            return $result;
        }

        public function getAnswersFromQuestion($idQuestion, $lang)
        {
            $methodLabel = 'setLabel'.ucfirst($lang);

            $SQLreq = 'SELECT Id_answer,
                              Id_question,
                              Label_'.$lang.' FROM Answers WHERE Id_Question = ?';
            $req = $this->m_dao->prepare($SQLreq);
            $req->execute(array($idQuestion));

            $result = array();
            while($data = $req->fetch())
            {
                $answer = new Answer;
                $answer->setId($data['Id_answer']);
                $answer->setIdQuestion($data['Id_question']);
                $answer->$methodLabel($data['Label_'.$lang]);

                $result[] = $answer;
            }

            return $result;
        }

        public function loadQuestions($idUser, $lang)
        {
            $methodLabel = 'setLabel'.ucfirst($lang);

            $reqId = $this->m_dao->prepare('SELECT Id_question FROM QuestionsOfUsers WHERE Id_user = ?');
            $reqId->execute(array($idUser));

            $questionIds = array();
            while($data = $reqId->fetch())
                $questionIds[] = $data['Id_question'];

            $result = array();
            $req = $this->m_dao->prepare('SELECT Label_'.$lang.' FROM Questions WHERE Id_question = ?');
            foreach($questionIds as $id)
            {
                $req->execute(array($id));
                $data = $req->fetch();

                $question = new Question;
                $question->setId($id);
                $question->$methodLabel($data['Label_'.$lang]);

                $result[] = $question;
            }

            return $result;
        }

        public function saveQuestions($idUser, $questions)
        {
            $req = $this->m_dao->prepare('INSERT INTO QuestionsOfUsers(Id_user,
                                                                       Id_question) VALUES(?, ?)');
            foreach($questions as $question)
                $req->execute(array($idUser,
                                    $question->getId()));
        }
    }
?>
