<?php
    class AjaxManager extends Manager
    {
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

		private function checkAvailabilityCoherency($fieldName, $subField, $id, $value)
		{
			$otherFieldName = ($fieldName == 'StartTime') ? 'EndTime' : 'StartTime';
			$statement = $this->m_dao->prepare("SELECT $fieldName, $otherFieldName FROM Availabilities WHERE Id_availability = ?");
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
			
			// Cas spécifique pour availabilities
			if ($fieldName == 'StartTime' || $fieldName == 'EndTime')
				$this->checkAvailabilityCoherency($fieldName, $subField, $id, $value);
			
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
			$statement->execute(array($id_classroom, $dateString, '00:00:00', '23:59:59'));
			
			return ($this->m_dao->lastInsertId());
		}
		
		public function addClassroom($ajaxInput)
		{
			$statement = $this->m_dao->prepare("INSERT INTO Classrooms(Name, Size) VALUES(?, ?)");
			$statement->execute(array('Nouvelle salle', 30));
						
			return ($this->m_dao->lastInsertId());
		}
	}
	
?>
