<?php
    class AjaxManager extends Manager
    {
        public function getUsername($begin)
        {
            $req = $this->m_dao->prepare('SELECT Id_user FROM Users WHERE MCQStatus != \'Visitor\' AND Id_user LIKE "' . $begin . '%"');
            $req->execute();

            $usernames = array();

            while($data = $req->fetch())
                $usernames[] = $data["Id_user"];

            return $usernames;
        }

		private function updateDate($tableName, $fieldName, $id, $idName, $idSub, $subField, $value)
		{
			$statement = $this->m_dao->prepare("SELECT $fieldName FROM $tableName WHERE $idName = ?");
			$statement->execute(array($id));
			
			if ($statement->getNumRows() == 0)
				throw new RuntimeException('Erreur: Problème d\'ID');
				
			$result = $statement->fetch();
			$date = new Date;
			$date->setFromMySQLResult($result[$fieldName]);

			switch ($subField)
			{
				case 'Day':
					$date->setDay(intval($value));
					break;
				case 'Month':
					$date->setMonth(intval($value));
					break;
				case 'Year':
					$date->setYear(intval($value));
					break;
				default:
					throw new RuntimeException('Erreur: Problème de sous champ');
			}
			
			$statement = $this->m_dao->prepare("UPDATE $tableName SET $fieldName = ? WHERE $idName = ?");
			$statement->execute(array($date->toStringMySQL(), $id));
		}
		
		private function updateTime($tableName, $fieldName, $id, $idName, $idSub, $subField, $value)
		{
			$statement = $this->m_dao->prepare("SELECT $fieldName FROM $tableName WHERE $idName = ?");
			$statement->execute(array($id));
			
			if ($statement->getNumRows() == 0)
				throw new RuntimeException('Erreur: Problème d\'ID');
				
			$result = $statement->fetch();
			$time = new Time;
			$time->setFromString($result[$fieldName]);
			switch ($subField)
			{
				case 'Hours':
					$time->setHours(intval($value));
					break;
				case 'Minutes':
					$time->setMinutes(intval($value));
					break;
				case 'Seconds':
					$time->setSeconds(intval($value));
					break;
				default:
					throw new RuntimeException('Erreur: Problème de sous champ');
			}
			
			$statement = $this->m_dao->prepare("UPDATE $tableName SET $fieldName = ? WHERE $idName = ?");
			$statement->execute(array($time->toStringMySQL(), $id));
		}

		private function checkScheduleCoherency($fieldName, $subField, $id, $value, $tableName, $idName)
		{
			$otherFieldName = ($fieldName == 'StartTime') ? 'EndTime' : 'StartTime';
			$statement = $this->m_dao->prepare("SELECT $fieldName, $otherFieldName FROM $tableName WHERE $idName = ?");
			$statement->execute(array($id));
			$results = $statement->fetch();
			
			$startTime = new Time;
			$endTime = new Time;

			$startTime->setFromString($results['StartTime']);
			$endTime->setFromString($results['EndTime']);
			
			if ($fieldName == 'StartTime')
				switch ($subField)
				{
					case 'Hours':
						$startTime->setHours(intval($value));
						break;
					case 'Minutes':
						$startTime->setMinutes(intval($value));
						break;
					case 'Seconds':
						$startTime->setSeconds(intval($value));
						break;
					default:
						throw new RuntimeException('Erreur: Problème de sous champ');
				}
			else
				switch ($subField)
				{
					case 'Hours':
						$endTime->setHours(intval($value));
						break;
					case 'Minutes':
						$endTime->setMinutes(intval($value));
						break;
					case 'Seconds':
						$endTime->setSeconds(intval($value));
						break;
					default:
						throw new RuntimeException('Erreur: Problème de sous champ');
				}
			if (Time::compare($startTime, $endTime) == 1)
				throw new RuntimeException('Erreur: Vous avez rentré une heure de début plus tardive que l\'heure de fin');
		}
		
		private function updateSubText($ajaxInput)
		{
			$tableName = $ajaxInput->getData('entry-name');
			$fieldName = $ajaxInput->getData('field-name');
			$id = $ajaxInput->getData('id');
			$idSub = $ajaxInput->getData('id-sub');
			$idName = $ajaxInput->getData('id-name');
			$subField = $ajaxInput->getData('subfield-name');
			$value = $ajaxInput->getValue();
			
			$isConfigDate = $ajaxInput->getData('is-config-date');

			// Cas spécifique pour les tables contenant StartTime ou EndTime
			if ($fieldName == 'StartTime' || $fieldName == 'EndTime')
				$this->checkScheduleCoherency($fieldName, $subField, $id, $value, $tableName, $idName);
			
            // Cas spécifique pour les date contenues dans les variables config: fieldName vaut Value
            if($isConfigDate == 'true')
            {
				$newValue = $this->updateDate($tableName, $fieldName, $id, $idName, $idSub, $subField, $value);
                return;
            }

			switch ($fieldName)
			{
				case 'Date':
					$newValue = $this->updateDate($tableName, $fieldName, $id, $idName, $idSub, $subField, $value);
					break;
				case 'StartTime':
				case 'EndTime':
					$newValue = $this->updateTime($tableName, $fieldName, $id, $idName, $idSub, $subField, $value);
					break;
				default:
					throw new Exception('Erreur cas spécial non géré');
			}
		}
		
		public function updateText($ajaxInput)
		{
			if ($ajaxInput->getData('subfield-name') != '')
				$this->updateSubText($ajaxInput);
			else
			{
				$tableName = $ajaxInput->getData('entry-name');
				$fieldName = $ajaxInput->getData('field-name');
				$id = $ajaxInput->getData('id');
				$idName = $ajaxInput->getData('id-name');
				$value = $ajaxInput->getValue();
				
				$statement = $this->m_dao->prepare("UPDATE $tableName SET $fieldName = ? WHERE $idName = ?");
				$statement->execute(array($value, $id));
			}
		}
		
		public function addAvailability($ajaxInput)
		{
			$id_classroom = $ajaxInput->getData('id');
			$statement = $this->m_dao->prepare('SELECT Id_classroom FROM Classrooms WHERE Id_classroom = ?');
			$statement->execute(array($id_classroom));
			if ($statement->getNumRows() == 0)
				throw new RuntimeException('Erreur: Problème d\'ID');
			
			$date = new Date(01, 01, (int)date('Y'));
			$dateString = $date->toStringMySQL();
			
			$statement = $this->m_dao->prepare("INSERT INTO Availabilities(Id_classroom, Date, StartTime, EndTime) VALUES(?, ?, ?, ?)");
			$statement->execute(array($id_classroom, $dateString, '00:00:00', '23:00:00'));
			
			return ($this->m_dao->lastInsertId());
		}
		
		public function addClassroom($ajaxInput)
		{
			$statement = $this->m_dao->prepare("INSERT INTO Classrooms(Name, Size) VALUES(?, ?)");
			$statement->execute(array('Nouvelle salle', 30));
						
			return ($this->m_dao->lastInsertId());
		}
		
		public function deleteEntry($ajaxInput)
		{
			$tableName = $ajaxInput->getData('entry-name');
			$id = $ajaxInput->getData('id');
			$idName = $ajaxInput->getData('id-name');
			
			$statement = $this->m_dao->prepare("DELETE FROM $tableName WHERE $idName = ?");
			$statement->execute(array($id));
		}

		public function getObjectToDelete($ajaxInput)
		{
			$tableName = $ajaxInput->getData('entry-name');
			$id = $ajaxInput->getData('id');
			$idName = $ajaxInput->getData('id-name');
			
			$statement = $this->m_dao->prepare("SELECT * FROM $tableName WHERE $idName = ?");
			$statement->execute(array($id));
    
            return $statement->fetch();
		}
		
		public function verifyCapacity($ajaxInput)
		{
			$reqCount = $this->m_dao->prepare('SELECT COUNT(DISTINCT Id_user) FROM Registrations WHERE Id_package = ?');
			$reqCount->execute(array($ajaxInput->getData('id')));
			$registrationsCount = $reqCount->fetch();
			
			if ($registrationsCount['COUNT(DISTINCT Id_user)'] > $ajaxInput->getValue())
				throw new Exception("Erreur: Vous ne pouvez mettre une capacité inférieure au nombre d'inscriptions déjà faites");
		}
	}
?>
