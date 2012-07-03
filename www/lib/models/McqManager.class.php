<?php
    class McqManager extends Manager
    {
        public function get($department = null, $schoolYear = null)
        {
            $requestSQL = 'SELECT Id_mcq,
                                  Department,
                                  SchoolYear,
                                  Name,
                                  Password,
                                  Date,
                                  StartTime,
                                  EndTime FROM MCQs';

            $paramsSQL = array();

            if($department)
            {
                $requestSQL .= ' WHERE Department = ?';
                $paramsSQL[] = $department;
            }

            if($schoolYear)
            {
                $connect = ($department) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' schoolYear = ?';
                $paramsSQL[] = $schoolYear;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL); 

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
                $mcq->setId($data['Id_mcq']);
                $mcq->setDepartment($data['Department']);
                $mcq->setSchoolYear($data['SchoolYear']);
                $mcq->setName($data['Name']);
                $mcq->setPassword($data['Password']);
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
                                                           Name,
                                                           Password,
                                                           Date,
                                                           StartTime,
                                                           EndTime) VALUES(?, ?, ?, ?, ?, ?, ?)');

            $req->execute(array($mcq->getDepartment(),
                                $mcq->getSchoolYear(),
                                $mcq->getName(),
                                $mcq->getPassword(),
                                $mcq->getDate()->toStringMySQL(),
                                $mcq->getStartTime()->toStringMySQL(),
                                $mcq->getEndTime()->toStringMySQL()));
        }
    }
?>
