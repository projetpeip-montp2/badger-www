<?php
    abstract class BackController extends ApplicationComponent
    {
        protected $m_action = '';
        protected $m_module = '';
        protected $m_page = null;
        protected $m_view = '';
        
        public function __construct(Application $app, $module, $action)
        {
            parent::__construct($app);
            
            $this->m_page = new Page($app);
            
            $this->setModule($module);
            $this->setAction($action);
            $this->setView($action);
        }
        
        public function execute()
        {
            $method = 'execute'.ucfirst($this->m_action);
            
            if (!is_callable(array($this, $method)))
                throw new RuntimeException('L\'action "'.$this->m_action.'" n\'est pas définie sur ce module');
            
            $this->$method($this->app()->httpRequest());
        }
        
        public function page()
        {
            return $this->m_page;
        }
        
        public function setModule($module)
        {
            if (!is_string($module) || empty($module))
                throw new InvalidArgumentException('Le module doit être une chaine de caractères valide');
            
            $this->m_module = $module;
        }
        
        public function setAction($action)
        {
            if (!is_string($action) || empty($action))
                throw new InvalidArgumentException('L\'action doit être une chaine de caractères valide');
            
            $this->m_action = $action;
        }
        
        public function setView($view)
        {
            if (!is_string($view) || empty($view))
                throw new InvalidArgumentException('La vue doit être une chaine de caractères valide');
            
            $this->m_view = $view;
            
            $this->m_page->setContentFile(dirname(__FILE__).'/../apps/'.$this->app()->name().'/modules/'.$this->m_module.'/views/'.$this->m_view.'.php');
        }
    } 
?>
