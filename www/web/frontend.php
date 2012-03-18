<?php
    try
    {
        require '../apps/frontend/FrontendApplication.class.php';
        
        $app = new FrontendApplication;
        $app->run(); 
    }

    catch(Exception $e)
    {
        echo 'An exception was thrown: ' . $e->getMessage();
        die();
    }
?>
