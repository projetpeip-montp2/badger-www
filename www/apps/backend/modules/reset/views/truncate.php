<p>Etes-vous sûr de vouloir vider la table : <?php echo $tableSelected;?></p>

<?php
    $form = new Form('', 'post');

    $form->add('checkbox', 'truncateDependencies')
         ->label('Voulez-vous vider les tables dont dépend celle-ci?');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', 'Valider');

    echo $form;
?>

