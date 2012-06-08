<p>Attention : Cette opération est irréversible et elle supprime les dépendances!</p>

<a href="Javascript:void(0)" onclick="changeCheckboxesStateForm('truncateForm', true);">Tout cocher</a>
/
<a href="Javascript:void(0)" onclick="changeCheckboxesStateForm('truncateForm', false);">Tout décocher</a>

<?php
    $form = new Form('', 'post');
    $form->setId("truncateForm");

    $form->beginFieldset();

    $num = count($checkboxes);
    
    for ($i=0; $i<$num; $i++)
    {
        // Don't remove or change the first $checkboxes[$i]! It is used to 
        // retrieve the tables selected
        $form->add('checkbox', $checkboxes[$i])
             ->label($checkboxes[$i]);
    }

    $form->add('submit', 'Vider');

    $form->endFieldset();

    echo $form->toString();
?>

<script type="text/javascript" src="/web/js/changeCheckboxesStateForm.js"></script>

