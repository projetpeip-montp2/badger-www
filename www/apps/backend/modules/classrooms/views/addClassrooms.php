<?php
    $form = new Form('', 'post');

    $form->add('file', 'vbmifareClassroomsCSV')
         ->label('Chemin des salles : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
