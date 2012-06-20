<?php
    class LectureManager extends Manager
    {
        public function get($idPackage = -1, $idLecture = -1)
        {
            $requestSQL = 'SELECT Id_lecture,
                                  Id_package,
                                  Id_availability,
                                  Lecturer, 
                                  Name_fr, 
                                  Name_en, 
                                  Description_fr,
                                  Description_en,
                                  Date,
                                  StartTime,
                                  EndTime FROM Lectures';
            $paramsSQL = array();

            if($idPackage != -1)
            {
                $requestSQL .= ' WHERE Id_package = ?';
                $paramsSQL[] = $idPackage;
            }

            if($idLecture != -1)
            {
                $connect = ($idPackage != -1) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' Id_lecture = ?';
                $paramsSQL[] = $idLecture;
            }
			
			$requestSQL .= ' ORDER BY Id_package, Id_lecture';

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL); 

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
                $lecture->setLecturer($data['Lecturer']);
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
                                                               Lecturer, 
                                                               Name_fr, 
                                                               Name_en, 
                                                               Description_fr,
                                                               Description_en,
                                                               Date,
                                                               StartTime,
                                                               EndTime) VALUES(?, 0, ?, ?, ?, ?, ?, ?, ?, ?)');

            foreach($lectures as $lecture)
                $req->execute(array($lecture->getIdPackage(),
                                    $lecture->getLecturer(),
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
            $req = $this->m_dao->prepare('UPDATE Lectures SET Lecturer = ?, 
                                                              Name_fr = ?, 
                                                              Name_en = ?,
                                                              Description_fr = ?,
                                                              Description_en = ?,
                                                              Date = ?,
                                                              StartTime = ?,
                                                              EndTime = ? WHERE Id_lecture = ?');

            $req->execute(array($lecture->getLecturer(),
                                $lecture->getName('fr'),
                                $lecture->getName('en'),
                                $lecture->getDescription('fr'),
                                $lecture->getDescription('en'),
                                $lecture->getDate()->toStringMySQL(),
                                $lecture->getStartTime()->toStringMySQL(),
                                $lecture->getEndTime()->toStringMySQL(),
                                $lecture->getId()));
        }

        public function delete($lectureId)
        {
            $req = $this->m_dao->prepare('DELETE FROM Lectures WHERE Id_lecture = ?');
            $req->execute(array($lectureId));
        }
		
		public function assignAvailability($array)
		{
			$req = $this->m_dao->prepare('UPDATE Lectures SET Id_availability = ? WHERE Id_lecture = ? AND Id_package = ?');
			$req->execute(array($array->idAvailability, $array->id, $array->idPackage));
		}

        public function unbindAvailability($idLecture)
        {
			$req = $this->m_dao->prepare('UPDATE Lectures SET Id_availability = 0 WHERE Id_lecture = ?');
			$req->execute(array($idLecture));
        }
    }
?>
