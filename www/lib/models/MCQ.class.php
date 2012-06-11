<?php
    class MCQ extends Record
    {
        private $m_department;
        private $m_schoolYear;
        private $m_date;
        private $m_startTime;
        private $m_endTime;

        public function setDepartment($department)
        {
            $this->m_department = $department;
        }

        public function getDepartment()
        {
            return $this->m_department;
        }

        public function setSchoolYear($schoolYear)
        {
            $this->m_schoolYear = $schoolYear;
        }

        public function getSchoolYear()
        {
            return $this->m_schoolYear;
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
