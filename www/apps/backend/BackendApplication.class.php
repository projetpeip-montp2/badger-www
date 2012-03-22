<?php
    require_once dirname(__FILE__).'/../../lib/autoload.php';
    
    class BackendApplication extends Application
    {
        public function __construct()
        {
            parent::__construct();
            
            $this->setName('backend');
        }
        
        public function run()
        {
            if (!$this->user()->existsAttribute('admin'))
            {
                require dirname(__FILE__).'/modules/connexion/ConnexionController.class.php';
                $controller = new ConnexionController($this, 'connexion', 'index');
            }
            else
            {
                $router = new Router($this);
                $controller = $router->getController();
            }

            $controller->execute();
            
            $this->httpResponse()->setPage($controller->page());
            $this->httpResponse()->send();
        }
    } 
?>
