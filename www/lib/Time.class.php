<?php
    class Time
    {
        private $m_hours;
        private $m_minutes;
        private $m_seconds;

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

        public function setFromString($time)
        {
            if(!Time::checkTime($time))
                throw new InvalidArgumentException('Invalid time format in Time setFromString');

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

        public function seconds()
        {
            return $this->m_seconds;
        }

        public function minutes()
        {
            return $this->m_minutes;
        }

        public function hours()
        {
            return $this->m_hours;
        }
        
        ////////////////////////////////////////////////////////////
        /// \brief Compare two times
        /// \params
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

        public function toStringMySQL()
        {
            return $this->m_hours.':'.$this->m_minutes.':'.$this->m_seconds;
        }

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

        static public function checkTime($time)
        {
            return preg_match('#[0-9]{2}:[0-9]{2}:[0-9]{2}#', $time);
        }
    }
?>

