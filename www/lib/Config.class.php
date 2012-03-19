<?php
    class Config extends ApplicationComponent
    {
        protected $m_vars = array();
        protected $m_isGlobal;
        protected $m_filename;

        public function __construct(Application $app, $isGlobal)
        {
            parent::__construct($app);
            
            $this->m_isGlobal = $isGlobal;
        }
        
        public function get($var)
        {
            // We don't create $filename before, because in constructor, app haven't got a name.
            $this->m_filename = ($this->m_isGlobal) ? dirname(__FILE__).'/../config.xml' :
                                                      dirname(__FILE__).'/../apps/'.$this->app()->name().'/config/app.xml';

            if (!$this->m_vars)
            {
                $xml = new DOMDocument;
                $xml->load($this->m_filename);
                
                $elements = $xml->getElementsByTagName('define');
                
                foreach ($elements as $element)
                {
                    $this->m_vars[$element->getAttribute('var')] = $element->getAttribute('value');
                }
            }
            
            if(!isset($this->m_vars[$var]))
                throw new RuntimeException('La variable de config "'. $var .'" n\'existe pas');

            return $this->m_vars[$var];
            
        }
    } 
?>
