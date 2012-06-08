<?php
    $form = new Form('', 'post');

    $choices = array();

    foreach($packages as $package)
        $choices[$package->getId()] = $package->getName($lang);

    $form->add('select', 'vbmifarePackage')
         ->label($TEXT['Reports_PackageSelection'] . ': ')
         ->choices($choices);

    $form->add('file', 'vbmifareReport')
         ->label($TEXT['Reports_Path'] . ': ');

    $form->add('submit', $TEXT['Form_Send']);

    echo $form->toString();
?>
