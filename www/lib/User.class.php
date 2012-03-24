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

        public function getAttribute($attr)
        {
            if(!$this->existsAttribute($attr))
                throw new RuntimeException('The attribute "' . $attr . '" does not exist in $_SESSION');

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
    }
?>
