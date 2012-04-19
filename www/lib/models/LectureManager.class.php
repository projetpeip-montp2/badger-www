<?php
    class LectureManager extends Manager
    {
        public function get($idPackage = -1)
        {
            $requestSQL = 'SELECT Id_lecture,
                                  Id_package,
                                  Id_availability, 
                                  Name_fr, 
                                  Name_en, 
                                  Description_fr,
                                  Description_en,
                                  Date,
                                  StartTime,
                                  EndTime FROM Lectures';

            if($idPackage != -1)
                $requestSQL .= ' WHERE Id_package = ' . $idPackage;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $lectures = array();

            while($data = $req->fetch())
            {
                $date = new Date;
                $date->setFromMySQLResult($data['Date']);

                $startTime = new Time;
                $startTime->setFromString($data['StartTime']);

                $endTime = new Time;
                $endTime->setFromString($data['EndTime']);

                $lecture = new Lecture;
                $lecture->setId($data['Id_lecture']);
                $lecture->setIdPackage($data['Id_package']);
                $lecture->setIdAvailability($data['Id_availability']);
                $lecture->setName('fr', $data['Name_fr']);
                $lecture->setName('en', $data['Name_en']);
                $lecture->setDescription('fr', $data['Description_fr']);
                $lecture->setDescription('en', $data['Description_en']);
                $lecture->setDate($date);
                $lecture->setStartTime($startTime);
                $lecture->setEndTime($endTime);

                $lectures[] = $lecture;
            }

            return $lectures;
        }

        public function save($lectures)
        {
            $req = $this->m_dao->prepare('INSERT INTO Lectures(Id_package,
                                                               Id_availability, 
                                                               Name_fr, 
                                                               Name_en, 
                                                               Description_fr,
                                                               Description_en,
                                                               Date,
                                                               StartTime,
                                                               EndTime) VALUES(?, 0, ?, ?, ?, ?, ?, ?, ?)');

            foreach($lectures as $lecture)
                $req->execute(array($lecture->getIdPackage(),
                                    $lecture->getName('fr'),
                                    $lecture->getName('en'),
                                    $lecture->getDescription('fr'),
                                    $lecture->getDescription('en'),
                                    $lecture->getDate()->toStringMySQL(),
                                    $lecture->getStartTime()->toStringMySQL(),
                                    $lecture->getEndTime()->toStringMySQL()));
        }

        public function update($lecture)
        {
            $req = $this->m_dao->prepare('UPDATE Lectures SET Name_fr = ?, 
                                                              Name_en = ?,
                                                              Description_fr = ?,
                                                              Description_en = ?,
                                                              Date = ?,
                                                              StartTime = ?,
                                                              EndTime = ? WHERE Id_lecture = ?');

            $req->execute(array($lecture->getName('fr'),
                                $lecture->getName('en'),
                                $lecture->getDescription('fr'),
                                $lecture->getDescription('en'),
                                $lecture->getDate()->toStringMySQL(),
                                $lecture->getStartTime()->toStringMySQL(),
                                $lecture->getEndTime()->toStringMySQL(),
                                $lecture->getId()));
        }

        public function delete($lectureIds)
        {
            $req = $this->m_dao->prepare('DELETE FROM Lectures WHERE Id_lecture = ?');

            foreach($lectureIds as $lectureId)
                $req->execute(array($lectureId));
        }
    }
?>
