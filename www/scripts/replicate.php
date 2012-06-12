<?php
    $classes = array('Database', 'Date', 'Time');

    foreach($classes as $class)
        require_once( dirname(__FILE__).'/../lib/' . $class . '.class.php' );

    function logReplication(&$daoENT, $statusCode)
    {
        $req = $daoENT->prepare('INSERT INTO ReplicationLogs(Date, Time, StatusCode) VALUES(CURDATE(), CURTIME(), ?)');
        $req->execute(array($statusCode));
    }

    function getDepartments(&$dao, $tableName)
    {
        $req = $dao->query('SHOW COLUMNS FROM ' . $tableName . ' LIKE \'Departement\'');
        $result = $req->fetch();

        // Remove enum( at the begining and ) at the end
        $tmp = $type = substr($result['Type'], 5, -1);

        return str_getcsv($tmp, ',', '\'');
    }

    try
    {
        $daoPoly = new Database('localhost', 'vbMifare', 'vbMifare2012', 'poly_repli');
        $daoENT = new Database('localhost', 'vbMifare', 'vbMifare2012', 'numsem');

        // Retrieve departments
        $dptsPoly = getDepartments($daoPoly, 'Users');
        $dptsENT = getDepartments($daoENT, 'UsersPolytech');

        // Check that our departments are include in Polytech departments
        // (i.e. no department remove in Polytech database)
        $include = true;
        foreach($dptsENT as $dptENT)
        {
            if(array_search($dptENT, $dptsPoly) === FALSE)
               $include = false; 
        }

        if(!$include)
        {
            logReplication($daoENT, 'DepartmentRemoveError');
            exit();
        }

        // Compute new departments if any
        $newDpts = array();
        foreach($dptsPoly as $dptPoly)
        {
            if(array_search($dptPoly, $dptsENT) === FALSE)
                $newDpts[] = $dptPoly;
        }

        // If there are new departments, update our departments
        if(!empty($newDpts))
        {
            $newDptsENT = array_merge($dptsENT, $newDpts);
            $req = $daoENT->query("ALTER TABLE UsersPolytech MODIFY Departement ENUM('" . implode("','", $newDptsENT) . "')");
        }

        // Retrieve students and Mifare from Polytech
        $req = $daoPoly->prepare('SELECT Username, Mifare FROM Users');
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
                // Load completely the student from Polytech db
                $req = $daoPoly->prepare('SELECT Username, Num_Etudiant, Mifare, Actif, VraiNom, VraiPrenom, Departement, anApogee FROM Users WHERE Username = ?');
                $req->execute(array($userPoly['Username']));

                $data = $req->fetch();

                // TODO: Doit-on aussi charger les personnes non actives

                // Now save the new student in our db
                $req = $daoENT->prepare('INSERT INTO UsersPolytech(Username, 
                                                                   Num_Etudiant, 
                                                                   Mifare, 
                                                                   Actif, 
                                                                   VraiNom, 
                                                                   VraiPrenom, 
                                                                   Departement, 
                                                                   anApogee) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
                $req->execute(array($data['Username'],
                                    $data['Num_Etudiant'],
                                    $data['Mifare'],
                                    $data['Actif'],
                                    $data['VraiNom'],
                                    $data['VraiPrenom'],
                                    $data['Departement'],
                                    $data['anApogee']));
            }

            if($foundUser && $mifareDifferent)
            {
                // TODO: Comment faire si il existe déjà une entrée dans la table
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
