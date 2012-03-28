<?php
    abstract class BackController extends ApplicationComponent
    {
        protected $m_action = '';
        protected $m_module = '';
        protected $m_page = null;
        protected $m_view = '';
        protected $m_managers = null;
        
        public function __construct(Application $app, $module, $action)
        {
            parent::__construct($app);
            
            $this->m_page = new Page($app);
            
            $this->m_managers = new Managers;
            $this->setModule($module);
            $this->setAction($action);
            $this->setView($action);
        }
        
        public function execute()
        {
            $method = 'execute'.ucfirst($this->m_action);
            
            if (!is_callable(array($this, $method)))
                throw new RuntimeException('The action "'. $this->m_action . '" does not exist in the module : ' . $this->m_module);
            
            $this->$method($this->app()->httpRequest());
        }
        
        public function page()
        {
            return $this->m_page;
        }
        
        public function setModule($module)
        {
            if (!is_string($module) || empty($module))
                throw new InvalidArgumentException('The module must be a valid string');
            
            $this->m_module = $module;
        }
        
        public function setAction($action)
        {
            if (!is_string($action) || empty($action))
                throw new InvalidArgumentException('The action must be a valid string');
            
            $this->m_action = $action;
        }
        
        public function setView($view)
        {
            if (!is_string($view) || empty($view))
                throw new InvalidArgumentException('The view must be a valid string');
            
            $this->m_view = $view;
            
            $this->m_page->setContentFile(dirname(__FILE__).'/../apps/'.$this->app()->name().'/modules/'.$this->m_module.'/views/'.$this->m_view.'.php');
        }
    } 
?>
