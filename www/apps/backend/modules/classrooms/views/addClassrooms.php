<?php
    $form = new Form('', 'post');

    $form->add('file', 'CSVFile')
         ->label('Chemin des salles : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
