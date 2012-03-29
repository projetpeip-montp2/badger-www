<p>Les tables suivante seront vidées : "<?php echo $tablesSelected;?>"</p>

<p>Attention : Cette opérations est irréversible!</p>

<?php
    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', 'Valider');

    echo $form->toString();
?>

