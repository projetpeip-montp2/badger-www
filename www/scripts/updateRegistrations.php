<?php
    date_default_timezone_set('Europe/Paris');

    $classes = array('Database', 'Date', 'Time', 'Record', 'models/Lecture', 'models/BadgingInformation');

    foreach($classes as $class)
        require_once( dirname(__FILE__).'/../lib/' . $class . '.class.php' );

    // Get usernames of all users that may need updates for their registrations
    function getUsernames(&$dao)
    {
        $req = $dao->query('SELECT Id_user FROM Users WHERE MCQStatus = \'CanTakeMCQ\'');

        $usernames = array();

        while($result = $req->fetch())
            $usernames[] = $result['Id_user'];

        return $usernames;
    }

    // Get Mifare numbers from the user
    // It includes the current contained in the UserPolytech table
    // and his former one contained in MifareHistory
    function getMifareNumbers(&$dao, $username)
    {
        $req = $dao->prepare('SELECT Mifare FROM UsersPolytech WHERE Username = ?');
        $req->execute(array($username));

        $mifareNumbers = array();

        $result = $req->fetch();

        // Get current Mifare for the User
        $mifareNumbers[] = $result['Mifare'];

        $req = $dao->prepare('SELECT Mifare FROM HistoryMifare WHERE Id_user = ?');
        $req->execute(array($username));

        while($result = $req->fetch())
            $mifareNumbers[] = $result['Mifare'];

        return $mifareNumbers;
    }

    // Return full badging information with the Mifare given in parameter
    function getBadgingInformations(&$dao, $mifare)
    {
        $req = $dao->prepare('SELECT Date, Time FROM BadgingInformations WHERE Mifare = ?');
        $req->execute(array($mifare));

        $badgingInformations = array();

        while($result = $req->fetch())
        {
            $date = new Date;
            $date->setFromMySQLResult($result['Date']);

            $time = new Time;
            $time->setFromString($result['Time']);

            $badgingInformation = new BadgingInformation;
            $badgingInformation->setMifare($mifare);
            $badgingInformation->setDate($date);
            $badgingInformation->setTime($time);

            $badgingInformations[] = $badgingInformation;
        }

        return $badgingInformations;
    }

    // Return ids of the lectures associated to the user's registrations
    // Object is not fully built because we do not need any other information
    function getRegistrations(&$dao, $username)
    {
        $req = $dao->prepare('SELECT Id_registration, Id_lecture FROM Registrations WHERE Id_user = ? AND Status = "Coming"');
        $req->execute(array($username));

        $registrations = array();
        
        while($data = $req->fetch())
        {
            $reg = array();
            $reg['Id_registration'] = $data['Id_registration'];
            $reg['Id_lecture'] = $data['Id_lecture'];
            $registrations[] = $reg;
        }

        return $registrations;
    }

    function getLecture(&$dao, $idLecture)
    {
        $req = $dao->prepare('SELECT * FROM Lectures WHERE Id_lecture = ?');
        $req->execute(array($idLecture));

        $data = $req->fetch();

        $date = new Date;
        $date->setFromMySQLResult($data['Date']);

        $startTime = new Time;
        $startTime->setFromString($data['StartTime']);

        $endTime = new Time;
        $endTime->setFromString($data['EndTime']);

        $lecture = new Lecture;
        $lecture->setId($data['Id_lecture']);
        $lecture->setIdPackage($data['Id_package']);
        $lecture->setIdAvailability($data['Id_availability']);
        $lecture->setLecturer($data['Lecturer']);
        $lecture->setName('fr', $data['Name_fr']);
        $lecture->setName('en', $data['Name_en']);
        $lecture->setDescription('fr', $data['Description_fr']);
        $lecture->setDescription('en', $data['Description_en']);
        $lecture->setDate($date);
        $lecture->setStartTime($startTime);
        $lecture->setEndTime($endTime);

        return $lecture;
    }

    function updateRegistration(&$dao, $idRegistration, $status)
    {
        $req = $dao->prepare('UPDATE Registrations SET Status = ? WHERE Id_registration = ?');
        $req->execute(array($status, $idRegistration));
    }

    // Check badging information validity
    function checkBadgingInformation($badgingInformation, $lecture)
    {
        return (
            (Date::compare($badgingInformation->getDate(), $lecture->getDate()) == 0) &&
            (Time::compare($badgingInformation->getTime(), $lecture->getStartTime()) >= 0) &&
            (Time::compare($badgingInformation->getTime(), $lecture->getEndTime()) <= 0)
          );
    }

// Begin update
    try
    {
        $database = new Database('localhost', 'vbMifare', 'vbMifare2012', 'numsem');
        $currentDate = Date::current();

        $usernames = getUsernames($database);

        foreach($usernames as $username)
        {
            $mifares = getMifareNumbers($database, $username);

            $badgingInformations = array();
            foreach($mifares as $mifare)
                $badgingInformations = array_merge($badgingInformations, getBadgingInformations($database, $mifare));

            $registrations = getRegistrations($database, $username);

            foreach($registrations as $registration)
            {
                $lecture = getLecture($database, $registration['Id_lecture']);
                // Check that currentDate < lectureDate
                if(Date::compare($currentDate, $lecture->getDate()) == 1)
                {
                    $flag = false;
                    foreach($badgingInformations as $badgingInformation)
                    {
                        if(checkBadgingInformation($badgingInformation, $lecture, $mifare))
                            $flag = true;
                    }

                    if($flag)
                        updateRegistration($database, $registration['Id_registration'], 'Present');
                    else
                        updateRegistration($database, $registration['Id_registration'], 'Absent');
                }
            }
        }
    }

    catch(Exception $e)
    {
        echo 'An exception was thrown: ' . $e->getMessage();
        die();
    }
?>
