<?php
    abstract class ApplicationComponent
    {
        private $m_app;
        
        public function __construct(Application $app)
        {
            $this->m_app = $app;
        }
        
        public function app()
        {
            return $this->m_app;
        }
    }
?>