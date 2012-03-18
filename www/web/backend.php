<?php
    try
    {
        require '../apps/backend/BackendApplication.class.php';
        
        $app = new BackendApplication;
        $app->run(); 
    }

    catch(Exception $e)
    {
        echo 'An exception was thrown: ' . $e->getMessage();
        die();
    }
?>
