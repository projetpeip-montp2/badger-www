<?php
    ////////////////////////////////////////////////////////////
    /// \brief Class Application
    ///
    /// An Application is an abstract class derivated into
    /// a Backend and a Frontend.
    /// When a page is being generated, an Application instance
    /// is created, and makes a bridge between the components.
    /// It also contains the HTTP Request that was received by the server
    /// and the Response sent back to the user.
    /// There are also information about the user himself encapsulated
    /// in the object m_user of the class User.
    ////////////////////////////////////////////////////////////
    abstract class Application
    {
        private $m_httpRequest;
        private $m_httpResponse;
        private $m_name;
        private $m_user;
        
        ////////////////////////////////////////////////////////////
        /// \brief function __construct()
        ///
        /// Default constructor of the Application class
        /// Initializes the variables contained in the class
        ////////////////////////////////////////////////////////////
        public function __construct()
        {
            date_default_timezone_set('Europe/Paris');

            $this->m_httpRequest = new HTTPRequest($this);
            $this->m_httpResponse = new HTTPResponse($this);
            $this->m_user = new User($this);
            
            $this->m_name = '';
        }

        ////////////////////////////////////////////////////////////
        /// \brief function run()
        ///
        /// Abstract function to run the application
        ////////////////////////////////////////////////////////////
        abstract public function run();

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
