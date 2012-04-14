<?php
    class Lecture extends Record
    {
        private $m_idLecture;
        private $m_idPackage;
        private $m_idAvailability;
        private $m_name_fr;
        private $m_name_en;
        private $m_description_fr;
        private $m_description_en;
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

        public function setNameFr($name_fr)
        {
            $this->m_name_fr = $name_fr;
        }

        public function getNameFr()
        {
            return $this->m_name_fr;
        }

        public function setNameEn($name_en)
        {
            $this->m_name_en = $name_en;
        }

        public function getNameEn()
        {
            return $this->m_name_en;
        }

        public function setDescriptionFr($description_fr)
        {
            $this->m_description_fr = $description_fr;
        }

        public function getDescriptionFr()
        {
            return $this->m_description_fr;
        }

        public function setDescriptionEn($description_en)
        {
            $this->m_description_en = $description_en;
        }

        public function getDescriptionEn()
        {
            return $this->m_description_en;
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



        static public function conflict(Lecture $lft, Lecture $rht)
        {
            $result;

            if(Date::compare($lft->getDate(), $rht->getDate()) == 0)
            {
                $t1 = $lft->getStartTime();
                $t2 = $lft->getEndTime();

                $t3 = $rht->getStartTime();
                $t4 = $rht->getEndTime();

                $result = (Time::compare($t4, $t1) == 1) && 
                          (Time::compare($t3, $t2) == -1);
            }
        
            else
                $result = false;

            return $result;
        }
    }
?>

