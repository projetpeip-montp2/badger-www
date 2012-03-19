<?php
    class Page extends ApplicationComponent
    {
        protected $m_filesToInclude = array();
        protected $m_contentFile;
        protected $m_vars = array();

        public function addVar($var, $value)
        {
            if (!is_string($var) || is_numeric($var) || empty($var))
                throw new InvalidArgumentException('Le nom de la variable doit être une chaine de caractère non nulle');
            
            $this->m_vars[$var] = $value;
        }

        public function addFileToInclude($filename)
        {
            $this->m_filesToInclude[] = $filename;
        }
        
        public function getGeneratedPage()
        {
            if (!file_exists($this->m_contentFile))
                throw new RuntimeException('La vue spécifiée n\'existe pas');
            
            $user = $this->app()->user();
            
            foreach($this->m_filesToInclude as $filename)
            {
                if(!empty($filename))
                    require_once $filename;
            }

            extract($this->m_vars);
            
            ob_start();
                require $this->m_contentFile;
            $content = ob_get_clean();
            
            ob_start();
                require dirname(__FILE__).'/../apps/'.$this->app()->name().'/templates/layout.php';
            return ob_get_clean();
        }
        
        public function setContentFile($contentFile)
        {
            if (!is_string($contentFile) || empty($contentFile))
                throw new InvalidArgumentException('La vue spécifiée est invalide');
            
            $this->m_contentFile = $contentFile;
        }
    } 
?>
