<?php
    $form = new Form('', 'post');

    $choices = array();

    foreach($classrooms as $classroom)
        $choices[$classroom->getId()] = $classroom->getName();

    $form->add('select', 'vbmifareClassroom')
         ->label('Sélection de la salle : ')
         ->choices($choices);

    $form->add('file', 'vbmifareAvailabilitiesCSV')
         ->label('Chemin des disponibilités : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
