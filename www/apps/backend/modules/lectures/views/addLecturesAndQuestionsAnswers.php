<?php
    $form = new Form('', 'post');

    $choices = array();

    foreach($packages as $package)
        $choices[$package->getId()] = $package->getNameFr();

    $form->add('select', 'vbmifarePackage')
         ->label('Sélection du package : ')
         ->choices($choices);

    $form->add('file', 'vbmifareLecturesCSV')
         ->label('Chemin des conférences : ');

    $form->add('file', 'vbmifareQuestionsAnswersCSV')
         ->label('Chemin des questions réponses : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
