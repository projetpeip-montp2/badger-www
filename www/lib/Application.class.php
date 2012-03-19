<?php
    abstract class Application
    {
        private $m_configLocal;
        private $m_configGlobal;
        private $m_httpRequest;
        private $m_httpResponse;
        private $m_name;
        private $m_user;
        
        public function __construct()
        {
            $this->m_configLocal = new Config($this, false);
            $this->m_configGlobal = new Config($this, true);

            $this->m_httpRequest = new HTTPRequest($this);
            $this->m_httpResponse = new HTTPResponse($this);
            $this->m_user = new User($this);
            
            $this->m_name = '';
        }
        
        abstract public function run();
        
        public function configLocal()
        {
            return $this->m_configLocal;
        }

        public function configGlobal()
        {
            return $this->m_configGlobal;
        }
        
        public function httpRequest()
        {
            return $this->m_httpRequest;
        }
        
        public function httpResponse()
        {
            return $this->m_httpResponse;
        }
        
        public function name()
        {
            return $this->m_name;
        }

        protected function setName($name)
        {
            $this->m_name = $name;
        }
        
        public function user()
        {
            return $this->m_user;
        }
    } 
?>
