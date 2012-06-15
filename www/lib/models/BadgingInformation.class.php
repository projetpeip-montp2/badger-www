<?php
    class BadgingInformation extends Record
    {
        private $m_mifare;
        private $m_date;
        private $m_time;
		
        public function setMifare($mifare)
        {
            $this->m_mifare = $mifare;
        }

        public function getMifare()
        {
            return $this->m_mifare;
        }

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
   }
?>
