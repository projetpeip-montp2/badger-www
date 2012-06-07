<?php
    ////////////////////////////////////////////////////////////
    /// \class Router
    ///
    /// \brief
    /// A Router is a class that inherits from the ApplicationComponent.
    /// The Router handles the URL to access the correct Controller
    /// from the routes.xml file
    ////////////////////////////////////////////////////////////
    class Router extends ApplicationComponent
    {
        ////////////////////////////////////////////////////////////
        /// \function getController
        ///
        /// \brief
        /// Loads the right Controller based on the right URL
        ////////////////////////////////////////////////////////////
        public function getController()
        {
            $dom = new DOMDocument;
            $dom->load(dirname(__FILE__).'/../apps/'.$this->app()->name().'/config/routes.xml');

            // Search the routes.xml file to find a route matching with the URL
            foreach ($dom->getElementsByTagName('route') as $route)
            {
                if (preg_match('`^'.$route->getAttribute('url').'$`', $this->app()->httpRequest()->requestURI(), $matches))                
                {
                    $module = $route->getAttribute('module');
                    $action = $route->getAttribute('action');
                    
                    $classname = ucfirst($module).'Controller';
                    $file = dirname(__FILE__).'/../apps/'.$this->app()->name().'/modules/'.$module.'/'.$classname.'.class.php';
                    
                    if (!file_exists($file))
                       throw new RuntimeException('The module used by the route "' . $route->getAttribute('url') . '" does not exist');
       
                    require $file;

                    $controller = new $classname($this->app(), $module, $action);

                    if ($route->hasAttribute('vars'))
                    {
                        $vars = explode(',', $route->getAttribute('vars'));
                        
                        foreach ($matches as $key => $match)
                        {
                            if ($key !== 0)
                                $this->app()->httpRequest()->addGetVar($vars[$key - 1], $match);
                        }
                    }
                    
                    break;
                }
            }
            if (!isset($controller))
                $this->app()->httpResponse()->redirect404();
            return $controller;
        }
    } 
?>
