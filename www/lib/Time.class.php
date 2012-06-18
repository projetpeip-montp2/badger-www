<?php
    ////////////////////////////////////////////////////////////
    /// \class Time
    ///
    /// \brief
    /// Utility class to handle times to generate string and
    /// SQL formats.
    /// Also allows to compare times in an easier way.
    ////////////////////////////////////////////////////////////
    class Time
    {
        private $m_hours;
        private $m_minutes;
        private $m_seconds;

        ////////////////////////////////////////////////////////////
        /// \function __construct
        ///
        /// \brief
        /// Default constructor of the Time class
        /// Initializes the variables of the class
        ///
        /// \param hours
        /// \param minutes
        /// \param seconds
        ////////////////////////////////////////////////////////////
        public function __construct($hours = 0, $minutes = 0, $seconds = 0)
        {
            $this->set($hours, $minutes, $seconds);
        }

        public function set($hours, $minutes, $seconds)
        {
            $this->setHours($hours);
            $this->setMinutes($minutes);
            $this->setSeconds($seconds);
        }

        ////////////////////////////////////////////////////////////
        /// \function setFromString
        ///
        /// \brief
        /// Parses a time from a string format (HH:MM:SS)
        /// Throws an exception if the format is not respected
        ///
        /// \param time Time sent in a string
        ////////////////////////////////////////////////////////////
        public function setFromString($time)
        {
            if(!Time::check($time))
                throw new InvalidArgumentException('Invalid time format in Time::setFromString : ' . $time);

            $timeArray = explode(':', $time);
            $this->set(intval($timeArray[0]), intval($timeArray[1]), intval($timeArray[2]));
        }

        public function setSeconds($seconds)
        {
            if(!(is_int($seconds)))
                throw new InvalidArgumentException('Invalid argument in Time setSeconds');
        
            if($seconds < 0 || $seconds > 60)
                throw new InvalidArgumentException('Invalid argument in Time seconds setter');
            else
                $this->m_seconds = $seconds;
        }

        public function setMinutes($minutes)
        {
            if(!(is_int($minutes)))
                throw new InvalidArgumentException('Invalid argument in Time setSeconds');

            if($minutes < 0 || $minutes > 60)
                throw new InvalidArgumentException('Invalid argument in Time minutes setter');
            else
                $this->m_minutes = $minutes;
        }

        public function setHours($hours)
        {
            if(!(is_int($hours)))
                throw new InvalidArgumentException('Invalid argument in Time setSeconds');

            if($hours < 0 || $hours >= 24)
                throw new InvalidArgumentException('Invalid argument in Time hours setter');
            else
                $this->m_hours = $hours;
        }

        public function seconds($addZero = FALSE)
        {
			if ($addZero)
				return str_pad($this->m_seconds, 2, '0', STR_PAD_LEFT);
            return $this->m_seconds;
        }

        public function minutes($addZero = FALSE)
        {
			if ($addZero)
				return str_pad($this->m_minutes, 2, '0', STR_PAD_LEFT);
            return $this->m_minutes;
        }

        public function hours($addZero = FALSE)
        {
			if ($addZero)
				return str_pad($this->m_hours, 2, '0', STR_PAD_LEFT);
            return $this->m_hours;
        }
		
        
        ////////////////////////////////////////////////////////////
        /// \function compare
        ///
        /// \brief
        /// Compares two times
        ///
        /// \param time1
        /// \param time2
        ///
        /// \return -1 if time1 < time2 | 1 if time1 > time2 | 0 if equal
        ////////////////////////////////////////////////////////////
        static public function compare(Time $time1 , Time $time2)
        {
            if($time1->hours() == $time2->hours())
                if($time1->minutes() == $time2->minutes())
                    if($time1->seconds() == $time2->seconds())
                        return 0;
                    else
                        return ($time1->seconds() > $time2->seconds()) ? 1 : -1;
                else
                    return ($time1->minutes() > $time2->minutes()) ? 1 : -1;
            else
                return ($time1->hours() > $time2->hours()) ? 1 : -1;
        }

        ////////////////////////////////////////////////////////////
        /// \function toStringMySQL
        ///
        /// \brief
        /// Gets the time as the MySQL time string format (HH:MM:SS)
        /// There is no padding with zeros
        ///
        /// \return time as a MySQL time string
        ////////////////////////////////////////////////////////////
        public function toStringMySQL()
        {
            return $this->m_hours.':'.$this->m_minutes.':'.$this->m_seconds;
        }

        ////////////////////////////////////////////////////////////
        /// \function __toString
        ///
        /// \brief
        /// Gets the time as the time string format (HH:MM:SS)
        ///
        /// \return time as a time string
        ////////////////////////////////////////////////////////////
        public function __toString()
        {
            $output = '';

            if($this->m_hours < 10)
                $output .= '0';
            $output .= $this->m_hours . ':';
            if($this->m_minutes < 10)
                $output .= '0';
            $output .= $this->m_minutes . ':';
            if($this->m_seconds < 10)
                $output .= '0';
            $output .= $this->m_seconds;

            return $output;
        }

        ////////////////////////////////////////////////////////////
        /// \function current
        ///
        /// \brief
        /// Gets the current time
        ///
        /// \return Current time
        ////////////////////////////////////////////////////////////
        static public function current()
        {
            $currentTime = new Time;
            $currentTime->setFromString(date('H:i:s'));

            return $currentTime;
        }

        ////////////////////////////////////////////////////////////
        /// \function overflowTime
        ///
        /// \brief
        /// Returns a Time made with:
        /// seconds > 60
        /// minutes > 60
        /// hours > 24
        ///
        /// \return Time
        ////////////////////////////////////////////////////////////
        static public function overflowTime($hours, $minutes, $seconds)
        {
            $time = new Time;

            $time->setSeconds(intval($seconds % 60));
            $time->setMinutes(intval(($minutes + ($seconds / 60)) % 60));
            $time->setHours(intval(($hours + (($minutes + ($seconds / 60)) / 60)) % 24));

            return $time;
        }

        ////////////////////////////////////////////////////////////
        /// \function check
        ///
        /// \brief
        /// Checks the time format of a string
        ///
        /// \return true if correct, false elsewhere
        ////////////////////////////////////////////////////////////
        static public function check($time)
        {
            return preg_match('#[0-9]{2}:[0-9]{2}:[0-9]{2}#', $time);
        }
    }
?>
