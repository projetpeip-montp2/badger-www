<?php
    class Page extends ApplicationComponent
    {
        protected $m_filesToInclude = array();
        protected $m_contentFile;
        protected $m_vars = array();
		private	  $m_isAjaxPage = FALSE;
		
        public function __construct(Application $app)
        {
            parent::__construct($app);
            
            if($app->name() == 'frontend')
                $this->addFileToInclude(dirname(__FILE__).'/../apps/frontend/lang/'.$app->user()->getAttribute('vbmifareLang').'.php');
        }

        public function addVar($var, $value)
        {
            if (!is_string($var) || is_numeric($var) || empty($var))
                throw new InvalidArgumentException('The name of the variable must be different than null');
            
            $this->m_vars[$var] = $value;
        }

        public function addFileToInclude($filename)
        {
            $this->m_filesToInclude[] = $filename;
        }
        
        public function getGeneratedPage()
        {
            if (!file_exists($this->m_contentFile))
                throw new RuntimeException('The needed view does not exist');
            
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
			if (!$this->m_isAjaxPage)
				require dirname(__FILE__).'/../apps/'.$this->app()->name().'/templates/layout.php';
			else
				echo $content;
            return ob_get_clean();
        }
        
        public function setContentFile($contentFile)
        {
            if (!is_string($contentFile) || empty($contentFile))
                throw new InvalidArgumentException('The needed view is invalid');
            
            $this->m_contentFile = $contentFile;
        }
		
		public function setIsAjaxPage($value)
		{
			$this->m_isAjaxPage = $value;
		}
    } 
?>
