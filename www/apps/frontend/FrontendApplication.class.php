<?php
    require_once dirname(__FILE__).'/../../lib/autoload.php';

    ////////////////////////////////////////////////////////////
    /// \class FrontendApplication
    ///
    /// \brief
    /// Defines the typical behavior of the Frontend application
    /// by implementing the run method
    ////////////////////////////////////////////////////////////
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
            $controller->page()->addFileToInclude(dirname(__FILE__).'/lang/' . $this->user()->getAttribute('vbmifareLang') . '.php');
            
            $this->httpResponse()->setPage($controller->page());
            $this->httpResponse()->send();
        }
    } 
?>
