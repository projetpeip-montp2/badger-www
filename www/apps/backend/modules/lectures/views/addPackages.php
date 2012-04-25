<p>
<?php
    $form = new Form('', 'post');

    $form->add('file', 'vbmifarePackagesCSV')
         ->label('Chemin du fichier : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
</p>
