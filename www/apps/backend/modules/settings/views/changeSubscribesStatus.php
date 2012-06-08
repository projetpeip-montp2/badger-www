<p>Statut courant des inscriptions : <?php echo $authorized ? 'Autorisé' : 'Interdit'; ?></p>

<?php
    $form = new Form('', 'post');

    $form->add('submit', $authorized ? 'Interdire' : 'Autoriser');

    echo $form->toString();
?>
