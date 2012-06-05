<?php
    session_start();
    
    class User extends ApplicationComponent
    {
        public function __construct(Application $app)
        {
            parent::__construct($app);

            /////////////////////////////////////////////////////////////////////
            // TODO: Retirer la ligne suivantes, qui ne sert que pour les tests
            $this->setAttribute('logon', 'victor.hiairrassary');
            //$this->setAttribute('logon', 'vbmifare');
            /////////////////////////////////////////////////////////////////////

            if(!$this->isLogged())
                throw new RuntimeException('User is not logged');

            if(!$this->existsAttribute('vbmifareLang'))
                $this->setAttribute('vbmifareLang', 'fr');
        }

        public function setAttribute($attr, $value)
        {
            $_SESSION[$attr] = $value;
        }

        public function unsetAttribute($attr)
        {
            if(!$this->existsAttribute($attr))
                throw new RuntimeException('The attribute "' . $attr . '" does not exist in $_SESSION for unset');

            unset($_SESSION[$attr]);
        }

        public function getAttribute($attr)
        {
            if(!$this->existsAttribute($attr))
                throw new RuntimeException('The attribute "' . $attr . '" does not exist in $_SESSION for get');

            return $_SESSION[$attr];
        }

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
