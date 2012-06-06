<?php
    try
    { 
        // Don't change the order of this includes!!!
        require_once dirname(__FILE__).'/../lib/CAS.php';
        require_once '../apps/frontend/FrontendApplication.class.php';
        require_once dirname(__FILE__).'/PHPCasInit.php';
  
        $app = new FrontendApplication;
        $app->run(); 
    }

    catch(Exception $e)
    {
        echo 'An exception was thrown: ' . $e->getMessage();
        die();
    }
?>
