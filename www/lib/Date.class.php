<?php
    class Date
    {
        private $m_year;
        private $m_month;
        private $m_day;

        public function __construct($day, $month, $year)
        {
            $this->set($day, $month, $year);
        }

        public function set($day, $month, $year)
        {
            $this->setYear($year);
            $this->setMonth($month);
            $this->setDay($day);
        }

        public function setFromString($date)
        {
            if(!preg_match('#[0-9]{2}-[0-9]{2}-[0-9]{4}#', $date))
                throw new InvalidArgumentException('Invalid date format in Date setFromString');
            else
                $dateArray = explode('-', $date);
                $this->set(intval($dateArray[0]), intval($dateArray[1]), intval($dateArray[2]));
        }

        public function setFromMySQLResult($date)
        {
            // Revert MySQL date format: YYYYMMDD -> DDMMYYYY
            $this->setFromString( implode('-', array_reverse(explode('-',$date))) );
        }

        public function setDay($day)
        {
            if(!(is_int($day)))
                throw new InvalidArgumentException('Invalid argument in Date setDay');

            // Define $lastDay depending on month and leap year
            $lastDay = 31;
            if($this->m_month == 2)
                $lastDay = $this->isLeapYear($this->m_year) ? 29 : 28;
            else if($this->m_month == 4 || $this->m_month == 6 || $this->m_month == 9 || $this->m_month == 11)
                $lastDay = 30;
        
            if($day <= 0 || $day > $lastDay)
                throw new InvalidArgumentException('Invalid argument in Date day setter');
            else
                $this->m_day = $day;
        }

        public function setMonth($month)
        {
            if(!(is_int($month)))
                throw new InvalidArgumentException('Invalid argument in Date setDay');

            if($month <= 0 || $month > 12)
                throw new InvalidArgumentException('Invalid argument in Date month setter');
            else
                $this->m_month = $month;
        }

        public function setYear($year)
        {
            if(!(is_int($year)))
                throw new InvalidArgumentException('Invalid argument in Date setDay');

            if($year < 0)
                throw new InvalidArgumentException('Negative argument in Date year setter');
            else
                $this->m_year = $year;
        }

        public function day()
        {
            return $this->m_day;
        }

        public function month()
        {
            return $this->m_month;
        }

        public function year()
        {
            return $this->m_year;
        }

        static public function isLeapYear(int $year)
        {
            if($year < 0)
                throw new InvalidArgumentException('Negative argument in leapYear function');
            else
                return ($year % 4 == 0) && ((year % 100 != 0) || (year % 400 == 0));
        }
        
        ////////////////////////////////////////////////////////////
        /// \brief Compare two dates
        /// \params
        /// \return -1 if date1 < date2 | 1 if date1 > date2 | 0 if equal
        ////////////////////////////////////////////////////////////
        static public function compare(Date $date1 , Date $date2)
        {
            if($date1->year() == $date2->year())
                if($date1->month() == $date2->month())
                    if($date1->day() == $date2->day())
                        return 0;
                    else
                        return ($date1->day() > $date2->day()) ? 1 : -1;
                else
                    return ($date1->month() > $date2->month()) ? 1 : -1;
            else
                return ($date1->year() > $date2->year()) ? 1 : -1;
        }

        public function __toString()
        {
            return $this->m_day.'-'.$this->m_month.'-'.$this->m_year;
        }
    }
?>
