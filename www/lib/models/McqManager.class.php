<?php
    class McqManager extends Manager
    {
        public function getQuestionsFromLecture($idLecture, $lang, $status = NULL)
        {
            $methodLabel = 'setLabel'.ucfirst($lang);

            $SQLreq = 'SELECT Id_question,
                              Label_'.$lang.' FROM Questions WHERE Id_Lecture = ?';
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

                $result[] = $question;
            }

            return $result;
        }
    }
?>
