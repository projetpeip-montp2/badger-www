<?php
    class Config extends ApplicationComponent
    {
        protected $vars = array();
        protected $isGlobal;
        protected $filename;

        public function __construct(Application $app, $isGlobal)
        {
            parent::__construct($app);
            
            $this->isGlobal = $isGlobal;
        }
        
        public function get($var)
        {
            // We don't create $filename before, because in constructor, app haven't got a name.
            $this->filename = ($this->isGlobal) ? dirname(__FILE__).'/../config.xml' :
                                                  dirname(__FILE__).'/../apps/'.$this->app()->name().'/config/app.xml';

            if (!$this->vars)
            {
                $xml = new DOMDocument;
                $xml->load($this->filename);
                
                $elements = $xml->getElementsByTagName('define');
                
                foreach ($elements as $element)
                {
                    $this->vars[$element->getAttribute('var')] = $element->getAttribute('value');
                }
            }
            
            if (isset($this->vars[$var]))
                return $this->vars[$var];
            
            return null;
        }
    } 
?>
