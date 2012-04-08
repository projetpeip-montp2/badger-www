<p>Les tables suivante seront vidées : "<?php echo $tablesSelected;?>"</p>

<p>Attention : </p>
<ul>
    <li>Cette opérations est irréversible!</li>
    <li>Elle ne supprime pas les dépendances des tables!</li>
</ul>

<?php
    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', 'Valider');

    echo $form->toString();
?>

