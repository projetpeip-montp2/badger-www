<?php
    class ClassroomManager extends Manager
    {
        public function get($id = -1)
        {
            $requestSQL = 'SELECT Id_classroom,
                                  Name,
                                  Size FROM Classrooms';

            if($id != -1)
                $requestSQL .= ' WHERE Id_classroom = ' . $id;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $classrooms = array();

            while($data = $req->fetch())
            {
                $classroom = new Classroom;
                $classroom->setId($data['Id_classroom']);
                $classroom->setName($data['Name']);
                $classroom->setSize($data['Size']);

                $classrooms[] = $classroom;
            }

            return $classrooms;
        }
		
		public function getWithAvailabilities($id = -1)
		{
			$requestSQL = 'SELECT	 classrooms.Id_classroom,
									 classrooms.Name,
									 classrooms.Size,
									 availabilities.Id_availability,
									 availabilities.Date,
									 availabilities.StartTime,
									 availabilities.EndTime
						   FROM   	 classrooms
						   LEFT JOIN availabilities ON classrooms.Id_classroom = availabilities.Id_classroom' . 
   						   (($id != -1) ? ' WHERE classrooms.Id_classroom = '.$id : '') . '
						   ORDER BY  classrooms.Name,
						   			 classrooms.Id_classroom,
									 availabilities.Date,
									 availabilities.StartTime';
									 
			$req = $this->m_dao->prepare($requestSQL);
			$req->execute();
			
			$classrooms = array();
			$curIdClassroom = -1;
			$i = -1;
			foreach ($req as $data)
            {
				if ($curIdClassroom != $data['Id_classroom'])
				{
					++$i;
					$curIdClassroom = $data['Id_classroom'];
					$classrooms[$i] = new Classroom;
					$classrooms[$i]->setId($curIdClassroom);
					$classrooms[$i]->setName($data['Name']);
					$classrooms[$i]->setSize($data['Size']);
					$classrooms[$i]->setAvailabilities(array());
				}
				
				$date = new Date;
				$startTime = new Time;
				$endTime = new Time;
				
				if (!empty($data['Date']))
				{
					$date->setFromMySQLResult($data['Date']);
					$startTime->setFromString($data['StartTime']);
					$endTime->setFromString($data['EndTime']);
				}

				$availability = new Availability;
				$availability->setId($data['Id_availability']);
				$availability->setIdClassroom($data['Id_classroom']);
				$availability->setDate($date);
				$availability->setStartTime($startTime);
				$availability->setEndTime($endTime);

				$availabilities = $classrooms[$i]->getAvailabilities();
				$availabilities[] = $availability;
				$classrooms[$i]->setAvailabilities($availabilities);
            }
            return $classrooms;
		}

        public function save($classrooms)
        {
            $req = $this->m_dao->prepare('INSERT INTO Classrooms(Name,
                                                                 Size) VALUES(?, ?)');

            foreach($classrooms as $classroom)
                $req->execute(array($classroom->getName(),
                                    $classroom->getSize()));
        }

        public function update($classroom)
        {
            $req = $this->m_dao->prepare('UPDATE Classrooms SET Name = ?, 
                                                                Size = ? WHERE Id_classroom = ?');

            $req->execute(array($classroom->getName(),
                                $classroom->getSize(),
                                $classroom->getId()));
        }

        public function delete($classroomIds)
        {
            $req = $this->m_dao->prepare('DELETE FROM Classrooms WHERE Id_classroom = ?');

            foreach($classroomIds as $classroomId)
                $req->execute(array($classroomId));
        }
    }
?>
