<?php
    class Managers
    {
        protected $m_dao = null;
        protected $m_managers = array();

        public function __construct()
        {
            $this->m_dao = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');
        }
        
        public function getManagerOf($module)
        {
            if (!is_string($module) || empty($module))
                throw new InvalidArgumentException('The module is invalid');
            
            if (!isset($this->m_managers[$module]))
            {
                $manager = $module.'manager';
                $this->m_managers[$module] = new $manager($this->m_dao);
            }
            
            return $this->m_managers[$module];
        }
    }
?>
