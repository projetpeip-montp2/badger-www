<h1>Changement des administrateurs.</h1>

<p>Par mesure de sécurité, l'utilisateur courant est automatiquement ajouté.</p>
<p>Séparer les administrateurs par un point-virgule.</p>

<?php
    $form = new Form('', 'post');

    $form->add('text', 'adminList')
         ->label('Nouvelle liste d\'administrateurs : ');

    $form->add('submit', 'Valider');

    echo $form->toString();
?>
