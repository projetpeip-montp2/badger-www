<?php
    ////////////////////////////////////////////////////////////
    /// \class Date
    ///
    /// \brief
    /// Utility class to handle dates to generate string and
    /// SQL formats.
    /// Also allows to compare dates in an easier way.
    ////////////////////////////////////////////////////////////
    class Date
    {
        private $m_year;
        private $m_month;
        private $m_day;

        ////////////////////////////////////////////////////////////
        /// \function __construct
        ///
        /// \brief
        /// Default constructor of the Date class
        /// Initializes the variables of the class
        ///
        /// \param day
        /// \param month
        /// \param year
        ////////////////////////////////////////////////////////////
        public function __construct($day = 1, $month = 1, $year = 2000)
        {
            $this->set($day, $month, $year);
        }

        public function set($day, $month, $year)
        {
            $this->setYear($year);
            $this->setMonth($month);
            $this->setDay($day);
        }

        ////////////////////////////////////////////////////////////
        /// \function setFromString
        ///
        /// \brief
        /// Parses a date from a string format (DD-MM-YYYY)
        /// Throws an exception if the format is not respected
        ///
        /// \param date Date sent in a string
        ////////////////////////////////////////////////////////////
        public function setFromString($date)
        {
            if(!Date::check($date))
                throw new InvalidArgumentException('Invalid date format in Date setFromString: ' . $date);
            else
                $dateArray = explode('-', $date);
                $this->set(intval($dateArray[0]), intval($dateArray[1]), intval($dateArray[2]));
        }

        ////////////////////////////////////////////////////////////
        /// \function setFromMySQLResult
        ///
        /// \brief
        /// Parses a date from the MySQL format (YYYY-MM-DD)
        ///
        /// \param date Date sent in a string
        ////////////////////////////////////////////////////////////
        public function setFromMySQLResult($date)
        {
            // Revert MySQL date format: YYYY-MM-DD -> DD-MM-YYYY
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
                throw new InvalidArgumentException('Invalid argument in Date setMonth');

            if($month <= 0 || $month > 12)
                throw new InvalidArgumentException('Invalid argument in Date month setter');
            else
                $this->m_month = $month;
        }

        public function setYear($year)
        {
            if(!(is_int($year)))
                throw new InvalidArgumentException('Invalid argument in Date setYear');

            if($year < 0)
                throw new InvalidArgumentException('Negative argument in Date year setter');
            else
                $this->m_year = $year;
        }

        public function day($addZero = FALSE)
        {
			if ($addZero)
				return str_pad($this->m_day, 2, '0', STR_PAD_LEFT);
            return $this->m_day;
        }

        public function month($addZero = FALSE)
        {
			if ($addZero)
				return str_pad($this->m_month, 2, '0', STR_PAD_LEFT);
            return $this->m_month;
        }

        public function year()
        {
            return $this->m_year;
        }

        ////////////////////////////////////////////////////////////
        /// \function isLeapYear
        ///
        /// \brief
        /// Tells if a year is a leap year or not
        ///
        /// \param year
        /// \return true if leap year, false elsewhere
        ////////////////////////////////////////////////////////////
        static public function isLeapYear($year)
        {
            if($year < 0)
                throw new InvalidArgumentException('Negative argument in leapYear function');
            else
                return ($year % 4 == 0) && (($year % 100 != 0) || ($year % 400 == 0));
        }
        
        ////////////////////////////////////////////////////////////
        /// \function compare
        ///
        /// \brief
        /// Compares two dates
        ///
        /// \param date1
        /// \param date2
        ///
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

        ////////////////////////////////////////////////////////////
        /// \function toStringMySQL
        ///
        /// \brief
        /// Gets the date as the MySQL date string format (YYYY-MM-DD)
        ///
        /// \return date as a MySQL date string
        ////////////////////////////////////////////////////////////
        public function toStringMySQL()
        {
            return $this->year().'-'.$this->month(TRUE) .'-'.$this->day(TRUE);
        }

        ////////////////////////////////////////////////////////////
        /// \function __toString
        ///
        /// \brief
        /// Gets the date as the date string format (DD-MM-YYYY)
        ///
        /// \return date as a date string
        ////////////////////////////////////////////////////////////
        public function __toString()
        {
            $output = '';

            if($this->m_day < 10)
                $output .= '0';
            $output .= $this->m_day . '-';
            if($this->m_month < 10)
                $output .= '0';
            $output .= $this->m_month . '-' . $this->m_year;

            return $output;
        }

        ////////////////////////////////////////////////////////////
        /// \function current
        ///
        /// \brief
        /// Gets the current date
        ///
        /// \return Current date
        ////////////////////////////////////////////////////////////
        static public function current()
        {
            $currentDate = new Date;
            $currentDate->setFromString(date('d-m-Y'));

            return $currentDate;
        }

        ////////////////////////////////////////////////////////////
        /// \function dayOfWeek
        ///
        /// \brief Returns the day of the week for a date
        ///
        /// \warning: Work only with date greater than 1582
        ///
        /// \param date
        ///
        /// \return 0: Sunday
        ///         1: Monday
        ///         2: Tuesday
        ///         3: Wednesday
        ///         4: Thursday
        ///         5: Friday
        ///         6: Saturday
        ////////////////////////////////////////////////////////////
        static public function dayOfWeek(Date $date)
        {
            $d = $date->day();
            $m = $date->month();
            $y = $date->year();

            $z = ($m<3) ? $y-1 : $y;

            $tmp = ((23 * $m)/9) + $d + 4 + $y + ($z/4) - ($z/100) + ($z/400);

            if($m >= 3)
                $tmp -= 2;

            return $tmp % 7;
        }

        ////////////////////////////////////////////////////////////
        /// \function check
        ///
        /// \brief
        /// Checks the date format of a string
        ///
        /// \return true if correct, false elsewhere
        ////////////////////////////////////////////////////////////
        static public function check($date)
        {
            return preg_match('#[0-9]{2}-[0-9]{2}-[0-9]{4}#', $date);
        }
    }
?>
