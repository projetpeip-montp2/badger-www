<?php
    abstract class Application
    {
        protected $configLocal;
        protected $configGlobal;
        protected $httpRequest;
        protected $httpResponse;
        protected $name;
        protected $user;
        
        public function __construct()
        {
            $this->configLocal = new Config($this, false);
            $this->configGlobal = new Config($this, true);

            $this->httpRequest = new HTTPRequest($this);
            $this->httpResponse = new HTTPResponse($this);
            $this->user = new User($this);
            
            $this->name = '';
        }
        
        abstract public function run();
        
        public function configLocal()
        {
            return $this->configLocal;
        }

        public function configGlobal()
        {
            return $this->configGlobal;
        }
        
        public function httpRequest()
        {
            return $this->httpRequest;
        }
        
        public function httpResponse()
        {
            return $this->httpResponse;
        }
        
        public function name()
        {
            return $this->name;
        }
        
        public function user()
        {
            return $this->user;
        }
    } 
?>
