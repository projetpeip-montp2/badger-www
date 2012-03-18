<?php
    class Page extends ApplicationComponent
    {
        protected $languageFileToInclude = "";
        protected $contentFile;
        protected $vars = array();
        
        public function addVar($var, $value)
        {
            if (!is_string($var) || is_numeric($var) || empty($var))
                throw new InvalidArgumentException('Le nom de la variable doit être une chaine de caractère non nulle');
            
            $this->vars[$var] = $value;
        }

        public function setLanguageFileToInclude($filename)
        {
            $this->languageFileToInclude = $filename;
        }
        
        public function getGeneratedPage()
        {
            if (!file_exists($this->contentFile))
                throw new RuntimeException('La vue spécifiée n\'existe pas');
            
            $user = $this->app->user();
            
            if(!empty($this->languageFileToInclude))
                require $this->languageFileToInclude;

            extract($this->vars);
            
            ob_start();
                require $this->contentFile;
            $content = ob_get_clean();
            
            ob_start();
                require dirname(__FILE__).'/../apps/'.$this->app->name().'/templates/layout.php';
            return ob_get_clean();
        }
        
        public function setContentFile($contentFile)
        {
            if (!is_string($contentFile) || empty($contentFile))
                throw new InvalidArgumentException('La vue spécifiée est invalide');
            
            $this->contentFile = $contentFile;
        }
    } 
?>
