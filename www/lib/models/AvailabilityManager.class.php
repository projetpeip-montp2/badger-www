<?php
    class AvailabilityManager extends Manager
    {
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
    }
?>
