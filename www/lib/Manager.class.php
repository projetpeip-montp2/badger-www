<?php
    ////////////////////////////////////////////////////////////
    /// \class Manager
    ///
    /// \brief
    /// A Manager is an abstract class derivated into
    /// concrete managers that will access the Database
    /// specifically for each module.
    ////////////////////////////////////////////////////////////
    abstract class Manager
    {
        protected $m_dao;
        
        public function __construct($dao)
        {
            $this->m_dao = $dao;
        }
    }
?>
