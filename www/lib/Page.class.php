<?php
    ////////////////////////////////////////////////////////////
    /// \class Page
    ///
    /// \brief
    /// A Page is a class that inherits from the ApplicationComponent.
    /// The Page contains variables sent from the Controller to the View,
    /// and specific information about what is sent to the user.
    ////////////////////////////////////////////////////////////
    class Page extends ApplicationComponent
    {
        protected $m_filesToInclude = array();
        protected $m_contentFile;
        protected $m_vars = array();
		private $m_isAjaxPage = FALSE;

        ////////////////////////////////////////////////////////////
        /// \function addVar
        ///
        /// \brief
        /// Creates a new var passed to the View from the Controller
        ///
        /// \param var Name of the variable
        /// \param value Value of the variable
        ////////////////////////////////////////////////////////////
        public function addVar($var, $value)
        {
            if (!is_string($var) || is_numeric($var) || empty($var))
                throw new InvalidArgumentException('The name of the variable must be different than null');
            
            $this->m_vars[$var] = $value;
        }

        ////////////////////////////////////////////////////////////
        /// \function addFileToInclude
        ///
        /// \brief
        /// Registers which file must be included in the page
        ///
        /// \param filename Name of the file
        ////////////////////////////////////////////////////////////
        public function addFileToInclude($filename)
        {
            $this->m_filesToInclude[] = $filename;
        }
        
        ////////////////////////////////////////////////////////////
        /// \function getGeneratedPage
        ///
        /// \brief
        /// Returns the page once it is generated (ie: file included etc)
        /// \return Return of the output buffer
        ////////////////////////////////////////////////////////////
        public function getGeneratedPage()
        {
			if ($this->m_isAjaxPage)
				return '';

			if (!file_exists($this->m_contentFile))
                throw new RuntimeException('The needed view does not exist');
            
            $user = $this->app()->user();
			
            foreach($this->m_filesToInclude as $fileToInclude)
            {
				if(!empty($fileToInclude))
                    require $fileToInclude;
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
                throw new InvalidArgumentException('The needed view is invalid');
            
            $this->m_contentFile = $contentFile;
        }
		
		public function setIsAjaxPage($value)
		{
			$this->m_isAjaxPage = $value;
		}
    } 
?>
