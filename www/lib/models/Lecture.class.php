<?php
    class Lecture
    {
        private $m_id;
        private $m_name;
        private $m_description;

        public function __construct($id , $name , $description)
        {
            $this->m_id = $id;
            $this->m_name = $name;
            $this->m_description = $description;
        }

        public function getId()
        {
            return $this->m_id;
        }

        public function getName()
        {
            return $this->m_name;
        }

        public function getDescription()
        {
            return $this->m_description;
        }
    }
?>
