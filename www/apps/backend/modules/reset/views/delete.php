<p>Attention : Cette opération est irréversible !</p>

<a href="Javascript:void(0)" onclick="changeCheckboxesStateForm('deleteForm', true);">Tout cocher</a>
/
<a href="Javascript:void(0)" onclick="changeCheckboxesStateForm('deleteForm', false);">Tout décocher</a>

<?php
    $form = new Form('', 'post');
    $form->setId("deleteForm");

    $form->beginFieldset();

    foreach($checkboxes as $checkbox)
    {
        // Don't remove or change the first $checkboxes[$i]! It is used to 
        // retrieve the directories selected
        $form->add('checkbox', $checkbox)
             ->label($checkbox);
    }

    $form->add('submit', 'Vider');

    $form->endFieldset();

    echo $form->toString();
?>

<script type="text/javascript" src="/web/js/changeCheckboxesStateForm.js"></script>

