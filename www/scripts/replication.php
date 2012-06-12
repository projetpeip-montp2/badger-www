<?php
    $classes = array('Database', 'Date', 'Time');

    foreach($classes as $class)
        require_once( dirname(__FILE__).'/../lib/' . $class . '.class.php' );

    function logReplication(&$daoENT, $statusCode)
    {
        $req = $daoENT->prepare('INSERT INTO ReplicationLogs(Date, Time, StatusCode) VALUES(CURDATE(), CURTIME(), ?)');
        $req->execute(array($statusCode));
    }

    try
    {

        // TODO: Mettre les bons paramètres
        $daoPoly = new Database('localhost', 'vbMifare', 'vbMifare2012', 'poly_repli');
        $daoENT = new Database('localhost', 'vbMifare', 'vbMifare2012', 'numsem');



        // TODO: Récupérer les nouveaux dpts s'il y en a




        // Retrieve students and Mifare from Polytech
        $req = $daoPoly->prepare('SELECT Username, Mifare FROM Polytech');
        $req->execute();
        $usersPoly = $req->fetchAll();

        // Retrieve students and Mifare from ENT
        $req = $daoENT->prepare('SELECT Username, Mifare FROM UsersPolytech');
        $req->execute();
        $usersEnt =$req->fetchAll();

        foreach($usersPoly as $userPoly)
        {
            $foundUser = false;
            $mifareDifferent = false;
            $oldMifare;
            foreach($usersEnt as $userEnt)
            {
                if($userPoly['Username'] == $userEnt['Username'])
                {
                    $foundUser = true;
                    if($userPoly['Mifare'] != $userEnt['Mifare'])
                    {
                        $mifareDifferent = true;
                        $oldMifare = $userEnt['Mifare'];
                    }
                }
            }

            if(!$foundUser)
            {
                // TODO: Inserer dans la table UsersPolytech que s'il est actif!
            }

            if($foundUser && $mifareDifferent)
            {
                // Inserer dans la table HistoryMifare
                $req = $daoENT->prepare('INSERT INTO HistoryMifare(Id_user, Mifare) VALUES(?, ?)');
                $req->execute(array($userPoly['Username'], $oldMifare));

                // Update dans la table UsersPolytech du nouveau Mifare
                $req = $daoENT->prepare('UPDATE UsersPolytech SET Mifare = ? WHERE Username = ?');
                $req->execute(array($userPoly['Mifare'], $userPoly['Username']));
            }
        }

        logReplication($daoENT, 'Success');
    }

    catch(Exception $e)
    {
        echo 'An exception was thrown: ' . $e->getMessage();
        die();
    }
?>
