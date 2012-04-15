<?php
    class Availability extends Record
    {
        private $m_idAvailability;
        private $m_idClassroom;
        private $m_date;
        private $m_startTime;
        private $m_endTime;

        public function setId($idAvailability)
        {
            $this->m_idAvailability = $idAvailability;
        }

        public function getId()
        {
            return $this->m_idAvailability;
        }

        public function setIdClassroom($idClassroom)
        {
            $this->m_idClassroom = $idClassroom;
        }

        public function getIdClassroom()
        {
            return $this->m_idClassroom;
        }

        public function setDate(Date $date)
        {
            $this->m_date = $date;
        }

        public function getDate()
        {
            return $this->m_date;
        }

        public function setStartTime(Time $startTime)
        {
            $this->m_startTime = $startTime;
        }

        public function getStartTime()
        {
            return $this->m_startTime;
        }

        public function setEndTime(Time $endTime)
        {
            $this->m_endTime = $endTime;
        }

        public function getEndTime()
        {
            return $this->m_endTime;
        }
    }
?>
