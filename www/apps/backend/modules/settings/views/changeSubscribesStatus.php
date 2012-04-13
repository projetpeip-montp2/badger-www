<h1>Changement du status des inscriptions</h1>

<p>
    Le formulaire ci-dessous permet d'autoriser ou d'interdire les inscriptions
    aux packages.
</p>

<p>Status des inscriptions : <?php echo $authorized ? 'Autorisé' : 'Interdit'; ?></p>

<?php
    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', $authorized ? 'Interdire' : 'Autoriser');

    echo $form->toString();
?>
