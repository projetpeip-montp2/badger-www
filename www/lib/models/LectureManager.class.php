<?php
    class LectureManager extends Manager
    {
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
