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
  'date' => 'lib/Date.class.php',
  'form' => 'lib/Form.class.php',
  'student' => 'lib/models/Student.class.php',
  'answer' => 'lib/models/Answer.class.php',
  'documentofpackage' => 'lib/models/DocumentOfPackage.class.php',
  'classroom' => 'lib/models/Classroom.class.php',
  'mcq' => 'lib/models/MCQ.class.php',
  'lecturemanager' => 'lib/models/LectureManager.class.php',
  'classroommanager' => 'lib/models/ClassroomManager.class.php',
  'package' => 'lib/models/Package.class.php',
  'question' => 'lib/models/Question.class.php',
  'availability' => 'lib/models/Availability.class.php',
  'lecture' => 'lib/models/Lecture.class.php',
  'registrationmanager' => 'lib/models/RegistrationManager.class.php',
  'answerofuser' => 'lib/models/AnswerOfUser.class.php',
  'usermanager' => 'lib/models/UserManager.class.php',
  'availabilitymanager' => 'lib/models/AvailabilityManager.class.php',
  'registration' => 'lib/models/Registration.class.php',
  'mcqmanager' => 'lib/models/McqManager.class.php',
  'resetmanager' => 'lib/models/ResetManager.class.php',
  'packagemanager' => 'lib/models/PackageManager.class.php',
  'user' => 'lib/User.class.php',
  'managers' => 'lib/Managers.class.php',
  'security' => 'lib/Security.class.php',
  'manager' => 'lib/Manager.class.php',
  'backcontroller' => 'lib/BackController.class.php',
  'time' => 'lib/Time.class.php',
  'record' => 'lib/Record.class.php',
  'router' => 'lib/Router.class.php',
  'database' => 'lib/Database.class.php',
);
        
        require dirname(__FILE__).'/../'.$classes[strtolower($class)];
    }
    
    spl_autoload_register('autoload'); 
?>
