<p>Cette page permet de vider certaines tables.</p>

<?php
    $form = new Form('', 'post');

    $form->add('select', 'vbmifareTable')
         ->label('Liste des tables : ')
         ->choices($select);

    $form->add('submit', 'Vider');

    echo $form;
?>

