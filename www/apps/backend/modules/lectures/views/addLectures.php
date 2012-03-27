<?php
    $form = new Form('', 'post');

    $form->add('file', 'vbmifareFileCSV')
         ->label('Chemin du fichier : ');

    $form->add('submit', 'Envoyer');

    echo $form;
?>
