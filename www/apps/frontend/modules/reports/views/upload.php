<?php
    $form = new Form('', 'post');

    $choices = array();

    foreach($packages as $package)
        $choices[$package->getId()] = $package->getName($lang);

    $form->add('select', 'vbmifarePackage')
         ->label('Sélection du package : ')
         ->choices($choices);

    $form->add('file', 'vbmifareReport')
         ->label('Chemin du rapport : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
