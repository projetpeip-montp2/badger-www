<?php
    ////////////////////////////////////////////////////////////
    /// \function autoload
    ///
    /// \brief
    /// This function is meant to be called by spl_autoload_register.
    /// It will automatically include the classes required during the
    /// execution of the script.
    ////////////////////////////////////////////////////////////
    function numsem_autoload($class)
    {
        $classes = array (
  'record' => 'lib/Record.class.php',
  'applicationcomponent' => 'lib/ApplicationComponent.class.php',
  'httpresponse' => 'lib/HTTPResponse.class.php',
  'time' => 'lib/Time.class.php',
  'application' => 'lib/Application.class.php',
  'backcontroller' => 'lib/BackController.class.php',
  'form' => 'lib/Form.class.php',
  'user' => 'lib/User.class.php',
  'router' => 'lib/Router.class.php',
  'date' => 'lib/Date.class.php',
  'page' => 'lib/Page.class.php',
  'httprequest' => 'lib/HTTPRequest.class.php',
  'managers' => 'lib/Managers.class.php',
  'archiveofpackagemanager' => 'lib/models/ArchiveOfPackageManager.class.php',
  'ajaxmanager' => 'lib/models/AjaxManager.class.php',
  'usermanager' => 'lib/models/UserManager.class.php',
  'registrationmanager' => 'lib/models/RegistrationManager.class.php',
  'documentofusermanager' => 'lib/models/DocumentOfUserManager.class.php',
  'lecturemanager' => 'lib/models/LectureManager.class.php',
  'badginginformationmanager' => 'lib/models/BadgingInformationManager.class.php',
  'package' => 'lib/models/Package.class.php',
  'answer' => 'lib/models/Answer.class.php',
  'mcq' => 'lib/models/MCQ.class.php',
  'resetmanager' => 'lib/models/ResetManager.class.php',
  'configmanager' => 'lib/models/ConfigManager.class.php',
  'questionmanager' => 'lib/models/QuestionManager.class.php',
  'imageofarchivemanager' => 'lib/models/ImageOfArchiveManager.class.php',
  'archiveofpackage' => 'lib/models/ArchiveOfPackage.class.php',
  'lecture' => 'lib/models/Lecture.class.php',
  'replicationlog' => 'lib/models/ReplicationLog.class.php',
  'replicationlogmanager' => 'lib/models/ReplicationLogManager.class.php',
  'imageofarchive' => 'lib/models/ImageOfArchive.class.php',
  'availabilitymanager' => 'lib/models/AvailabilityManager.class.php',
  'registration' => 'lib/models/Registration.class.php',
  'student' => 'lib/models/Student.class.php',
  'question' => 'lib/models/Question.class.php',
  'availability' => 'lib/models/Availability.class.php',
  'mcqmanager' => 'lib/models/McqManager.class.php',
  'packagemanager' => 'lib/models/PackageManager.class.php',
  'answerofuser' => 'lib/models/AnswerOfUser.class.php',
  'ajaxinput' => 'lib/models/AjaxInput.class.php',
  'documentofpackagemanager' => 'lib/models/DocumentOfPackageManager.class.php',
  'documentofpackage' => 'lib/models/DocumentOfPackage.class.php',
  'answermanager' => 'lib/models/AnswerManager.class.php',
  'documentofuser' => 'lib/models/DocumentOfUser.class.php',
  'classroommanager' => 'lib/models/ClassroomManager.class.php',
  'classroom' => 'lib/models/Classroom.class.php',
  'database' => 'lib/Database.class.php',
  'manager' => 'lib/Manager.class.php',
  'zipfile' => 'lib/zipfile.class.php',
);
        
        require dirname(__FILE__).'/../'.$classes[strtolower($class)];
    }
    
    spl_autoload_register('numsem_autoload'); 
?>
