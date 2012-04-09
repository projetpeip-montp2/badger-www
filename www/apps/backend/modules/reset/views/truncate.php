<h1>Vider les tables</h1>

<p>Cette page permet de vider certaines tables.</p>

<p>Attention : </p>
<ul>
    <li>Cette opération est irréversible!</li>
    <li>Elle ne supprime pas les dépendances des tables (par exemples supprimer les questions invalidera les réponses)!</li>
</ul>

<?php
    $form = new Form('', 'post');

    $form->beginFieldset();

    $num = count($checkboxes);
    
    for ($i=0; $i<$num; $i++)
    {
        $form->add('checkbox', $checkboxes[$i])
             ->label($checkboxes[$i]);
    }

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', 'Vider');

    $form->endFieldset();

    echo $form->toString();
?>

