<?php
    class Lecture extends Record
    {
        private $m_idLecture;
        private $m_idPackage;
        private $m_idAvailability;
        private $m_lecturer;
        private $m_name;
        private $m_description;
        private $m_date;
        private $m_startTime;
        private $m_endTime;

        public function setId($idLecture)
        {
            $this->m_idLecture = $idLecture;
        }

        public function getId()
        {
            return $this->m_idLecture;
        }

        public function setIdPackage($idPackage)
        {
            $this->m_idPackage = $idPackage;
        }

        public function getIdPackage()
        {
            return $this->m_idPackage;
        }

        public function setIdAvailability($idAvailability)
        {
            $this->m_idAvailability = $idAvailability;
        }

        public function getIdAvailability()
        {
            return $this->m_idAvailability;
        }

        public function setLecturer($lecturer)
        {
            $this->m_lecturer = $lecturer;
        }

        public function getLecturer()
        {
            return $this->m_lecturer;
        }

        public function setName($lang, $name)
        {
            $this->m_name[$lang] = $name;
        }

        public function getName($lang)
        {
            return $this->m_name[$lang];
        }

        public function setDescription($lang, $description)
        {
            $this->m_description[$lang] = $description;
        }

        public function getDescription($lang)
        {
            return $this->m_description[$lang];
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

        public function canUseAvailabilitiy(Availability $avail)
        {
            if(Date::compare($this->getDate(), $avail->getDate()) == 0)
            {
                $t1 = $this->getStartTime();
                $t2 = $this->getEndTime();

                $t3 = $avail->getStartTime();
                $t4 = $avail->getEndTime();

                return (Time::compare($t1, $t3) >= 0) && 
                       (Time::compare($t2, $t4) <= 0);
            }
        
            return false;
        }
    }
?>
