<?php
    // Commented because the CAS system have already a session_start()
    //session_start();
    
    ////////////////////////////////////////////////////////////
    /// \class User
    ///
    /// \brief
    /// A User is a class that inherits from the ApplicationComponent.
    /// The User handles session variables, small messages displayed for
    /// a specific user, his status (logged or not), etc.
    ////////////////////////////////////////////////////////////
    class User extends ApplicationComponent
    {
        ////////////////////////////////////////////////////////////
        /// \function __construct
        ///
        /// \brief
        /// Default constructor of the User class
        ///
        /// \param app Reference to the Application
        ////////////////////////////////////////////////////////////
        public function __construct(Application $app)
        {
            parent::__construct($app);

            if(!$this->isLogged())
                throw new RuntimeException('User is not logged');

            if(!$this->existsAttribute('vbmifareLang'))
                $this->setAttribute('vbmifareLang', 'fr');
        }

        ////////////////////////////////////////////////////////////
        /// \function setAttribute
        ///
        /// \brief
        /// Creates a new session variable
        ///
        /// \param attr Name of the variable
        /// \param value Value of the variable
        ////////////////////////////////////////////////////////////
        public function setAttribute($attr, $value)
        {
            $_SESSION[$attr] = $value;
        }

        ////////////////////////////////////////////////////////////
        /// \function unsetAttribute
        ///
        /// \brief
        /// Deletes a new session variable
        ///
        /// \param attr Name of the variable
        ////////////////////////////////////////////////////////////
        public function unsetAttribute($attr)
        {
            if(!$this->existsAttribute($attr))
                throw new RuntimeException('The attribute "' . $attr . '" does not exist in $_SESSION for unset');

            unset($_SESSION[$attr]);
        }

        ////////////////////////////////////////////////////////////
        /// \function getAttribute
        ///
        /// \brief
        /// Access a new session variable
        ///
        /// \param attr Name of the variable
        ///
        /// \return Value of the variable
        ////////////////////////////////////////////////////////////
        public function getAttribute($attr)
        {
            if(!$this->existsAttribute($attr))
                throw new RuntimeException('The attribute "' . $attr . '" does not exist in $_SESSION for get');

            return $_SESSION[$attr];
        }

        ////////////////////////////////////////////////////////////
        /// \function existsAttribute
        ///
        /// \brief
        /// Tells whether a session variable exists or not
        ///
        /// \param attr Name of the variable
        ///
        /// \return true if exists, false elsewhere
        ////////////////////////////////////////////////////////////
        public function existsAttribute($attr)
        {
            return isset($_SESSION[$attr]);
        }

        public function isLogged()
        {
            return $this->existsAttribute('logon');
        }

        public function setFlashError($value)
        {
            $this->setAttribute('vbmifareFlash', $value);
            $this->setAttribute('vbmifareFlashType', 'error');
        }

        public function setFlashWarning($value)
        {
            $this->setAttribute('vbmifareFlash', $value);
            $this->setAttribute('vbmifareFlashType', 'warning');
        }

        public function setFlashInfo($value)
        {
            $this->setAttribute('vbmifareFlash', $value);
            $this->setAttribute('vbmifareFlashType', 'info');
        }

        public function getFlash()
        {
            $flash = $this->getAttribute('vbmifareFlash');
            $this->unsetAttribute('vbmifareFlash');
            
            return $flash;
        }

        public function getFlashType()
        {
            $flashType = $this->getAttribute('vbmifareFlashType');
            
            return $flashType;
        }
        
        public function hasFlash()
        {
            return $this->existsAttribute('vbmifareFlash');
        }
    }
?>
