<?php
    class BadgingInformationManager extends Manager
    {
        public function insert($mifare, $date, $time)
        {
            $req = $this->m_dao->prepare('INSERT INTO BadgingInformations(Mifare, Date, Time) VALUES(?, ?, ?)');
            $req->execute(array($mifare, $date->toStringMySQL(), $time->toStringMySQL())); 
        }
    }
?>
