<?php
    ////////////////////////////////////////////////////////////
    /// \class HTTPResponse
    ///
    /// \brief
    /// An HTTPResponse is a class that inherits from the
    /// ApplicationComponent.
    /// The HTTPResponse is an interface to add HTTP headers,
    /// redirect to another page or to specific error pages,
    /// create cookies, etc.
    ////////////////////////////////////////////////////////////
    class HTTPResponse extends ApplicationComponent
    {
        protected $m_page;
        
        ////////////////////////////////////////////////////////////
        /// \function addHeader
        ///
        /// \brief
        /// Adds a new header
        ///
        /// \param header
        ////////////////////////////////////////////////////////////
        public function addHeader($header)
        {
            header($header);
        }

        ////////////////////////////////////////////////////////////
        /// \function redirect
        ///
        /// \brief
        /// Redirects to the location specified in parameter
        ///
        /// \param location Page to go to
        ////////////////////////////////////////////////////////////
        public function redirect($location)
        {
            header('Location: '.$location);
            exit;
        }

        ////////////////////////////////////////////////////////////
        /// \function redirect404
        ///
        /// \brief
        /// Redirects to the 404 error (Page not found)
        ////////////////////////////////////////////////////////////
        public function redirect404()
        {
            $this->m_page = new Page($this->app());
            $this->m_page->setContentFile(dirname(__FILE__).'/../errors/404.html');
            
            $this->addHeader('HTTP/1.0 404 Not Found');
            
            $this->send();
        }

        ////////////////////////////////////////////////////////////
        /// \function redirect403
        ///
        /// \brief
        /// Redirects to the 403 error (Insuffiscient rights)
        ////////////////////////////////////////////////////////////
        public function redirect403()
        {
            $this->redirect('/error/403.html');
        }

        ////////////////////////////////////////////////////////////
        /// \function send
        ///
        /// \brief
        /// Sends the page to the user
        ////////////////////////////////////////////////////////////
        public function send()
        {
            exit($this->m_page->getGeneratedPage());
        }
        
        public function setPage(Page $page)
        {
            $this->m_page = $page;
        }
        
        // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
        public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
        {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        }
    } 
?>
