<p>Statut courant des inscriptions : <?php echo $authorized ? 'Autorisé' : 'Interdit'; ?></p>

<?php
    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', $authorized ? 'Interdire' : 'Autoriser');

    echo $form->toString();
?>
