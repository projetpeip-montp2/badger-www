<?php
    class ConnexionManager extends Manager
    {
        public function retrieveInformations($logon)
        {
            //$dbPolytech = new Database('localhost', 'vbMifare', 'vbMifare2012', 'Polytech');

            $db_vbMifare = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            // Si on ne trouve pas l'étudiant dans la bdd vbmifare
            //     S'il existe une entré dans la bdd polytech et qu'elle est autorisé            
            //         Alors on fait un insert dans la bdd vbmifare et renvoi l'élève
            // 
            //     Sinon 
            //         Erreur
            //
            // Alors 
            //     On renvoi l'élève charger depuis la bdd polytech
            //
                
            return null;
        }
    }
?>
