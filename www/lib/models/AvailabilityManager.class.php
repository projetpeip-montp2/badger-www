<?php
    class AvailabilityManager extends Manager
    {
        public function get($idClassroom = -1, $idAvailability = -1)
        {
            $requestSQL = 'SELECT Id_availability,
                                  Id_classroom,
                                  Date,
                                  StartTime,
                                  EndTime FROM Availabilities';

            $paramsSQL = array();

            if($idClassroom != -1)
            {
                $requestSQL .= ' WHERE Id_classroom = ?';
                $paramsSQL[] = $idClassroom;
            }

            if($idAvailability != -1)
            {
                $connect = ($idClassroom != -1) ? 'AND' : 'WHERE';
                $requestSQL .= ' ' . $connect .' Id_availability = ?';
                $paramsSQL[] = $idAvailability;
            }

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute($paramsSQL); 
			
            $availabilities = array();

            while($data = $req->fetch())
            {
                $date = new Date;
                $date->setFromMySQLResult($data['Date']);

                $startTime = new Time;
                $startTime->setFromString($data['StartTime']);

                $endTime = new Time;
                $endTime->setFromString($data['EndTime']);

                $availability = new Availability;
                $availability->setId($data['Id_availability']);
                $availability->setIdClassroom($data['Id_classroom']);
                $availability->setDate($date);
                $availability->setStartTime($startTime);
                $availability->setEndTime($endTime);

                $availabilities[] = $availability;
            }

            return $availabilities;
        }

        public function save($availabilities)
        {
            $req = $this->m_dao->prepare('INSERT INTO Availabilities(Id_classroom,
                                                                     Date, 
                                                                     StartTime,
                                                                     EndTime) VALUES(?, ?, ?, ?)');

            foreach($availabilities as $availability)
            {
                $req->execute(array($availability->getIdClassroom(),
                                    $availability->getDate()->toStringMySQL(),
                                    $availability->getStartTime()->toStringMySQL(),
                                    $availability->getEndTime()->toStringMySQL()));
            }
        }

       public function update($availability)
        {
            $req = $this->m_dao->prepare('UPDATE Availabilities SET Date = ?, 
                                                                    StartTime = ?,
                                                                    EndTime = ? WHERE Id_availability = ?');

            $req->execute(array($availability->getDate()->toStringMySQL(),
                                $availability->getStartTime()->toStringMySQL(),
                                $availability->getEndTime()->toStringMySQL(),
                                $availability->getId()));
        }

        public function delete($availabilityIds)
        {
            $req = $this->m_dao->prepare('DELETE FROM Availabilities WHERE Id_availability = ?');

            foreach($availabilityIds as $availabilityId)
                $req->execute(array($availabilityId));
        }
    }
?>
