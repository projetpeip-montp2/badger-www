<h1>Ajout de département</h1>
<p>Depuis cette page, il est possible d'ajouter un département pouvant passer les QCM.</p>
<p>Rappel: Il faut utiliser le nom exact du département dans l'énumération de la table Users de la base de données de Polytech.</p>

<?php
    $form = new Form('', 'post');

    $form->add('text', 'Name')
         ->label('Nom du département : ');

    $form->add('submit', 'Ajouter');

    echo $form->toString();
?>
