<?php
    class Classroom extends Record
    {
        private $m_idClassroom;
        private $m_name;
        private $m_size;

        public function setId($idClassroom)
        {
            $this->m_idClassroom = $idClassroom;
        }

        public function getId()
        {
            return $this->m_idClassroom;
        }

        public function setName($name)
        {
            $this->m_name = $name;
        }

        public function getName()
        {
            return $this->m_name;
        }

        public function setSize($size)
        {
            if(!is_int($size) && $size <= 0)
                throw new InvalidArgumentException('Invalid argument in Classroom::setSize');

            $this->m_size = $size;
        }

        public function getSize()
        {
            return $this->m_size;
        }
    }
?>
