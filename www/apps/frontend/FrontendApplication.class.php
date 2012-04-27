<?php
    require_once dirname(__FILE__).'/../../lib/autoload.php';
    
    class FrontendApplication extends Application
    {
        public function __construct()
        {
            parent::__construct();
            
            $this->setName('frontend');
        }
        
        public function run()
        {
            if (!$this->user()->existsAttribute('vbmifareStudent'))
            {
                require dirname(__FILE__).'/modules/connection/ConnectionController.class.php';
                $controller = new ConnectionController($this, 'connexion', 'index');
            }
            else
            {
                $router = new Router($this);
                $controller = $router->getController();
            }

            require_once dirname(__FILE__).'/BackControllerFrontend.class.php';

            $controller->getInfos();
            $controller->execute();
            
            $this->httpResponse()->setPage($controller->page());
            $this->httpResponse()->send();
        }
    } 
?>
