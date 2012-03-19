<?php
    session_start();
    
    class User extends ApplicationComponent
    {
        public function __construct(Application $app)
        {
            parent::__construct($app);

            /////////////////////////////////////////////////////////////////////
            // TODO: Retirer les lignes suivantes, qui ne sert que pour les tests
            $this->setAttribute('logon', 'vbmifare');

            if(!$this->isAvailable())
                throw new RuntimeException('User is not allowed to be on this web site');
            /////////////////////////////////////////////////////////////////////

            if(!$this->isLogged())
                throw new RuntimeException('User is not logged');

            if(!$this->existsAttribute('lang'))
                $this->setAttribute('lang', 'fr');
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
        
        public function isAdmin()
        {
            return in_array($this->getAttribute('logon'), explode(';', $this->app()->configGlobal()->get('adminUsersList')));
        }

        


        // TODO: Supprimer aussi cette fonction qui n'aura plus lieu d'être.
        public function isAvailable()
        {
            return in_array($this->getAttribute('logon'), explode(';', $this->app()->configGlobal()->get('availablesUsersList')));
        }
    } 
?>
