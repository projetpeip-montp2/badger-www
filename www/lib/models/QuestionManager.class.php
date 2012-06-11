<?php
    class QuestionManager extends Manager
    {
        public function get($idPackage, $status = NULL)
        {
            $SQLreq = 'SELECT Id_question,
                              Id_package,
                              Label_fr,
                              Label_en,
                              Status FROM Questions WHERE Id_package = ?';
            $SQLparams = array($idPackage);
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
                $question->setIdPackage($data['Id_package']);
                $question->setLabel('fr', $data['Label_fr']);
                $question->setLabel('en', $data['Label_en']);
                $question->setStatus($data['Status']);

                $result[] = $question;
            }

            return $result;
        }

        public function save($question)
        {
            if(!in_array($question->getStatus(), array('Possible', 'Impossible', 'Obligatory')))
                throw new InvalidArgumentException('Invalid question status in McqManager::saveQuestion');

            $req = $this->m_dao->prepare('INSERT INTO Questions(Id_package,
                                                                Label_fr, 
                                                                Label_en, 
                                                                Status) VALUES(?, ?, ?, ?)');

            $req->execute(array($question->getIdPackage(),
                                $question->getLabel('fr'),
                                $question->getLabel('en'),
                                $question->getStatus()));

            return $this->m_dao->lastInsertId();
        }

        public function saveQuestionsOfUser($idUser, $questions)
        {
            $req = $this->m_dao->prepare('INSERT INTO QuestionsOfUsers(Id_user,
                                                                       Id_question) VALUES(?, ?)');
            foreach($questions as $question)
                $req->execute(array($idUser,
                                    $question->getId()));
        }

        public function loadQuestionsOfUser($idUser)
        {
            $reqId = $this->m_dao->prepare('SELECT Id_question FROM QuestionsOfUsers WHERE Id_user = ?');
            $reqId->execute(array($idUser));

            $questionIds = array();
            while($data = $reqId->fetch())
                $questionIds[] = $data['Id_question'];

            $result = array();
            $req = $this->m_dao->prepare('SELECT Label_fr, Label_en FROM Questions WHERE Id_question = ?');
            foreach($questionIds as $id)
            {
                $req->execute(array($id));
                $data = $req->fetch();

                $question = new Question;
                $question->setId($id);
                $question->setLabel('fr', $data['Label_fr']);
                $question->setLabel('en', $data['Label_en']);

                $result[] = $question;
            }

            return $result;
        }
    }
?>
