<?php
    class ReplicationLog extends Record
    {
        private $m_date;
        private $m_time;
        private $m_statusCode;
        private $m_comment;

        public function setDate(Date $date)
        {
            $this->m_date = $date;
        }

        public function getDate()
        {
            return $this->m_date;
        }

        public function setTime(Time $time)
        {
            $this->m_time = $time;
        }

        public function getTime()
        {
            return $this->m_time;
        }

        public function setStatusCode($statusCode)
        {
            $this->m_statusCode = $statusCode;
        }

        public function getStatusCode()
        {
            return $this->m_statusCode;
        }

        public function setComment($comment)
        {
            $this->m_comment = $comment;
        }

        public function getComment()
        {
            return $this->m_comment;
        }
    }
?>
