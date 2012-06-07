<?php
    ////////////////////////////////////////////////////////////
    /// \class Managers
    ///
    /// \brief
    /// The Managers class allows to handle all the specific
    /// managers associated to a module through a simple interface.
    ////////////////////////////////////////////////////////////
    class Managers
    {
        protected $m_dao = null;
        protected $m_managers = array();

        ////////////////////////////////////////////////////////////
        /// \function __construct
        ///
        /// \brief
        /// Default constructor of the Managers class
        /// Sets the connection to the Database
        ////////////////////////////////////////////////////////////
        public function __construct()
        {
            $this->m_dao = new Database('localhost', 'vbMifare', 'vbMifare2012', 'numsem');
        }
        
        ////////////////////////////////////////////////////////////
        /// \function getManagerOf
        ///
        /// \brief
        /// Access a specific manager by its name
        ///
        /// \param module Name of the module
        ///
        /// \return Manager of the specific module
        ////////////////////////////////////////////////////////////
        public function getManagerOf($module)
        {
            if (!is_string($module) || empty($module))
                throw new InvalidArgumentException('The module is invalid');
            
            if (!isset($this->m_managers[$module]))
            {
                $manager = $module.'manager';
                $this->m_managers[$module] = new $manager($this->m_dao);
            }
            
            return $this->m_managers[$module];
        }
    }
?>
