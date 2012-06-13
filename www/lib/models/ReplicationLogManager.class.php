<?php
    class ReplicationLogManager extends Manager
    {
        public function get()
        {
            $req = $this->m_dao->query('SELECT * FROM ReplicationLogs');

            $result = array();
            while($data = $req->fetch())
            {
                $date = new Date;
                $date->setFromMySQLResult($data['Date']);

                $time = new Time;
                $time->setFromString($data['Time']);

                $log = new ReplicationLog;
                $log->setDate($date);
                $log->setTime($time);
                $log->setComment($data['Comment']);
                $log->setStatusCode($data['StatusCode']);

                $result[] = $log;
            }

            return $result;
        }
    }
?>
