<?php
    function autoload($class)
    {
        $classes = array (
  'application' => 'lib/Application.class.php',
  'applicationcomponent' => 'lib/ApplicationComponent.class.php',
  'httpresponse' => 'lib/HTTPResponse.class.php',
  'config' => 'lib/Config.class.php',
  'page' => 'lib/Page.class.php',
  'httprequest' => 'lib/HTTPRequest.class.php',
  'student' => 'lib/models/Student.class.php',
  'answer' => 'lib/models/Answer.class.php',
  'classroom' => 'lib/models/Classroom.class.php',
  'connectionmanager' => 'lib/models/ConnectionManager.class.php',
  'question' => 'lib/models/Question.class.php',
  'lecture' => 'lib/models/Lecture.class.php',
  'answerofuser' => 'lib/models/AnswerOfUser.class.php',
  'availabilty' => 'lib/models/Availabilty.class.php',
  'registration' => 'lib/models/Registration.class.php',
  'document' => 'lib/models/Document.class.php',
  'user' => 'lib/User.class.php',
  'security' => 'lib/Security.class.php',
  'manager' => 'lib/Manager.class.php',
  'backcontroller' => 'lib/BackController.class.php',
  'record' => 'lib/Record.class.php',
  'router' => 'lib/Router.class.php',
  'database' => 'lib/Database.class.php',
);
        
        require dirname(__FILE__).'/../'.$classes[strtolower($class)];
    }
    
    spl_autoload_register('autoload'); 
?>
