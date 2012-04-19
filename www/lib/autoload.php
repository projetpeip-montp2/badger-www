<?php
    function autoload($class)
    {
        $classes = array (
  'record' => 'lib/Record.class.php',
  'applicationcomponent' => 'lib/ApplicationComponent.class.php',
  'httpresponse' => 'lib/HTTPResponse.class.php',
  'time' => 'lib/Time.class.php',
  'application' => 'lib/Application.class.php',
  'backcontroller' => 'lib/BackController.class.php',
  'security' => 'lib/Security.class.php',
  'form' => 'lib/Form.class.php',
  'user' => 'lib/User.class.php',
  'router' => 'lib/Router.class.php',
  'date' => 'lib/Date.class.php',
  'page' => 'lib/Page.class.php',
  'httprequest' => 'lib/HTTPRequest.class.php',
  'managers' => 'lib/Managers.class.php',
  'usermanager' => 'lib/models/UserManager.class.php',
  'registrationmanager' => 'lib/models/RegistrationManager.class.php',
  'documentofusermanager' => 'lib/models/DocumentOfUserManager.class.php',
  'lecturemanager' => 'lib/models/LectureManager.class.php',
  'package' => 'lib/models/Package.class.php',
  'answer' => 'lib/models/Answer.class.php',
  'mcq' => 'lib/models/MCQ.class.php',
  'resetmanager' => 'lib/models/ResetManager.class.php',
  'configmanager' => 'lib/models/ConfigManager.class.php',
  'lecture' => 'lib/models/Lecture.class.php',
  'availabilitymanager' => 'lib/models/AvailabilityManager.class.php',
  'registration' => 'lib/models/Registration.class.php',
  'student' => 'lib/models/Student.class.php',
  'question' => 'lib/models/Question.class.php',
  'availability' => 'lib/models/Availability.class.php',
  'mcqmanager' => 'lib/models/McqManager.class.php',
  'packagemanager' => 'lib/models/PackageManager.class.php',
  'answerofuser' => 'lib/models/AnswerOfUser.class.php',
  'documentofpackagemanager' => 'lib/models/DocumentOfPackageManager.class.php',
  'documentofpackage' => 'lib/models/DocumentOfPackage.class.php',
  'documentofuser' => 'lib/models/DocumentOfUser.class.php',
  'classroommanager' => 'lib/models/ClassroomManager.class.php',
  'classroom' => 'lib/models/Classroom.class.php',
  'database' => 'lib/Database.class.php',
  'manager' => 'lib/Manager.class.php',
);
        
        require dirname(__FILE__).'/../'.$classes[strtolower($class)];
    }
    
    spl_autoload_register('autoload'); 
?>
