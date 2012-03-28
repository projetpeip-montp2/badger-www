<?php
    class LectureManager extends Manager
    {
        public function get($lang, $id = -1)
        {
            $methodName = 'setName'.ucfirst($lang);
            $methodDescription = 'setDescription'.ucfirst($lang);

            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $requestSQL = 'SELECT Id_lecture,
                                  Id_availability, 
                                  Name_'.$lang.', 
                                  Lecturer,
                                  Description_'.$lang.', 
                                  Date,
                                  StartTime,
                                  EndTime,
                                  Tags FROM Lectures';

            if($id != -1)
                $requestSQL .= ' WHERE Id_lecture = ' . $id;

            $req = $db_vbMifare->prepare($requestSQL);
            $req->execute(); 

            $lectures = array();

            while($data = $req->fetch())
            {
                $lecture = new Lecture;
                $lecture->setId($data['Id_lecture']);
                $lecture->setIdAvailability($data['Id_availability']);
                $lecture->$methodName($data['Name_'.$lang]);
                $lecture->setLecturer($data['Lecturer']);
                $lecture->$methodDescription($data['Description_'.$lang]);
                $lecture->setDate($data['Date']);
                $lecture->setStartTime($data['StartTime']);
                $lecture->setEndTime($data['EndTime']);
                $lecture->setTags($data['Tags']);

                $lectures[] = $lecture;
            }

            return $lectures;
        }

        public function save($lectures)
        {
            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $req = $db_vbMifare->prepare('INSERT INTO Lectures(Id_availability, 
                                                               Name_fr, 
                                                               Name_en, 
                                                               Lecturer,
                                                               Description_fr,
                                                               Description_en,
                                                               Date,
                                                               StartTime,
                                                               EndTime,
                                                               Tags) VALUES(0, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

            foreach($lectures as $lecture)
                $req->execute(array($lecture->getNameFr(),
                                    $lecture->getNameEn(),
                                    $lecture->getLecturer(),
                                    $lecture->getDescriptionFr(),
                                    $lecture->getDescriptionEn(),
                                    $lecture->getDate(),
                                    $lecture->getStartTime(),
                                    $lecture->getEndTime(),
                                    $lecture->getTags()));
        }
    }
?>
