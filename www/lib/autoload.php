<?php
    function autoload($class)
    {
        $classes = array (
  'record' => 'lib/Record.class.php',
  'applicationcomponent' => 'lib/ApplicationComponent.class.php',
  'httpresponse' => 'lib/HTTPResponse.class.php',
  'application' => 'lib/Application.class.php',
  'backcontroller' => 'lib/BackController.class.php',
  'security' => 'lib/Security.class.php',
  'form' => 'lib/Form.class.php',
  'user' => 'lib/User.class.php',
  'router' => 'lib/Router.class.php',
  'date' => 'lib/Date.class.php',
  'page' => 'lib/Page.class.php',
  'httprequest' => 'lib/HTTPRequest.class.php',
  'config' => 'lib/Config.class.php',
  'availabilty' => 'lib/models/Availabilty.class.php',
  'connectionmanager' => 'lib/models/ConnectionManager.class.php',
  'lecturemanager' => 'lib/models/LectureManager.class.php',
  'answer' => 'lib/models/Answer.class.php',
  'document' => 'lib/models/Document.class.php',
  'lecture' => 'lib/models/Lecture.class.php',
  'registration' => 'lib/models/Registration.class.php',
  'student' => 'lib/models/Student.class.php',
  'question' => 'lib/models/Question.class.php',
  'mcqmanager' => 'lib/models/McqManager.class.php',
  'answerofuser' => 'lib/models/AnswerOfUser.class.php',
  'classroom' => 'lib/models/Classroom.class.php',
  'database' => 'lib/Database.class.php',
  'manager' => 'lib/Manager.class.php',
);
        
        require dirname(__FILE__).'/../'.$classes[strtolower($class)];
    }
    
    spl_autoload_register('autoload'); 
?>
