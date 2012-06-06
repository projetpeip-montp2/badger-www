<?php
    $form = new Form('', 'post');

    $choices = array();

    foreach($packages as $package)
        $choices[$package->getId()] = $package->getName('fr');

    $form->add('select', 'PackageList')
         ->label('Sélection du package : ')
         ->choices($choices);

    $form->add('file', 'zipFile')
         ->label('Chemin du fichier zip : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
