<?php
    abstract class Manager
    {
        protected $m_dao;
        
        public function __construct($dao)
        {
            $this->m_dao = $dao;
        }
    }
?>
