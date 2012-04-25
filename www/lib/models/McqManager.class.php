<?php
    class McqManager extends Manager
    {
        public function get($department = null, $schoolYear = null)
        {
            $requestSQL = 'SELECT Department,
                                  SchoolYear,
                                  Date,
                                  StartTime,
                                  EndTime FROM MCQs';

            if($department && $schoolYear)
                $requestSQL .= ' WHERE Departement = ' . $departement . ' AND SchoolYear = ' . $schoolYear;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $mcqs = array();

            while($data = $req->fetch())
            {
                $date = new Date;
                $date->setFromMySQLResult($data['Date']);

                $startTime = new Time;
                $startTime->setFromString($data['StartTime']);

                $endTime = new Time;
                $endTime->setFromString($data['EndTime']);

                $mcq = new MCQ;
                $mcq->setDepartment($data['Department']);
                $mcq->setSchoolYear($data['SchoolYear']);;
                $mcq->setDate($date);
                $mcq->setStartTime($startTime);
                $mcq->setEndTime($endTime);

                $mcqs[] = $mcq;
            }

            return $mcqs;
        }

        public function save($mcq)
        {
            $req = $this->m_dao->prepare('INSERT INTO MCQs(Department,
                                                           SchoolYear,
                                                           Date,
                                                           StartTime,
                                                           EndTime) VALUES(?, ?, ?, ?, ?)');

            $req->execute(array($mcq->getDepartment(),
                                $mcq->getSchoolYear(),
                                $mcq->getDate()->toStringMySQL(),
                                $mcq->getStartTime()->toStringMySQL(),
                                $mcq->getEndTime()->toStringMySQL()));
        }

        public function update($mcq)
        {
            $req = $this->m_dao->prepare('UPDATE MCQs SET Department = ?,
                                                          SchoolYear = ?,
                                                          Date = ?,
                                                          StartTime = ?,
                                                          EndTime = ? WHERE Department = ? AND SchoolYear = ?');

            $req->execute(array($mcq->getDepartment(),
                                $mcq->getSchoolYear(),
                                $mcq->getDate()->toStringMySQL(),
                                $mcq->getStartTime()->toStringMySQL(),
                                $mcq->getEndTime()->toStringMySQL(),
                                $mcq->getDepartment(),
                                $mcq->getSchoolYear()));
        }

        public function delete($mcqs)
        {
            $req = $this->m_dao->prepare('DELETE FROM MCQs WHERE Department = ? AND SchoolYear = ?');

            foreach($mcqs as $mcq)
                $req->execute(array($mcq['Department'],$mcq['SchoolYear']));
        }

        public function deleteQuestions($idPackage)
        {
            $req = $this->m_dao->prepare('DELETE FROM Questions WHERE Id_package = ?');
            $req->execute(array($idPackage));
        }

        public function deleteAnswers($idQuestion)
        {
            $req = $this->m_dao->prepare('DELETE FROM Answers WHERE Id_question = ?');
            $req->execute(array($idQuestion));
        }

        public function deleteQuestionsOfUsers($idQuestion)
        {
            $req = $this->m_dao->prepare('DELETE FROM QuestionsOfUsers WHERE Id_question = ?');
            $req->execute(array($idQuestion));
        }

        public function deleteAnswersOfUsers($idQuestion)
        {
            $req = $this->m_dao->prepare('DELETE FROM AnswersOfUsers WHERE Id_question = ?');
            $req->execute(array($idQuestion));
        }

        public function getQuestionsFromPackage($idPackage, $status = NULL)
        {
            $SQLreq = 'SELECT Id_question,
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
                $question->setLabel('fr', $data['Label_fr']);
                $question->setLabel('en', $data['Label_en']);
                $question->setStatus($data['Status']);

                $result[] = $question;
            }

            return $result;
        }

        public function getAnswersFromQuestion($idQuestion)
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

        public function saveQuestionsOfUser($idUser, $questions)
        {
            $req = $this->m_dao->prepare('INSERT INTO QuestionsOfUsers(Id_user,
                                                                       Id_question) VALUES(?, ?)');
            foreach($questions as $question)
                $req->execute(array($idUser,
                                    $question->getId()));
        }

        public function saveQuestion($question)
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

        public function saveAnswers($answers)
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
