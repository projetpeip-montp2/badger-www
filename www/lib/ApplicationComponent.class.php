<?php
    ////////////////////////////////////////////////////////////
    /// \brief Class ApplicationComponent
    ///
    /// An ApplicationComponent is an abstract class derivated to
    /// create a concrete components such as the User, or
    /// the HTTP Resquests and Responses.
    /// Each ApplicationComponent has a reference to the Application
    /// itself, allowing to go upward in the hierarchy and access
    /// another component.
    ////////////////////////////////////////////////////////////
    abstract class ApplicationComponent
    {
        private $m_app;
        
        ////////////////////////////////////////////////////////////
        /// \brief function __construct()
        ///
        /// Default constructor of the ApplicationComponent class
        /// Initializes the reference to the Application
        ///
        /// \param app Reference to the Application
        ////////////////////////////////////////////////////////////
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
