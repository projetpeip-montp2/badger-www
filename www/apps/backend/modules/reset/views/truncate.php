<h1>Vider les tables</h1>

<p>Cette page permet de vider certaines tables.</p>

<p>Attention : </p>
<ul>
    <li>Cette opération est irréversible!</li>
    <li>Elle ne supprime pas les dépendances des tables (par exemples supprimer les questions invalidera les réponses)!</li>
</ul>

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

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', 'Vider');

    $form->endFieldset();

    echo $form->toString();
?>

<script type="text/javascript">
    function changeCheckboxesStateForm(container_id, state)
    {
        var checkboxes = document.getElementById(container_id).getElementsByTagName('input');

        for(var i=0; i < checkboxes.length; i++)
        {
            if(checkboxes[i].type == 'checkbox')
                checkboxes[i].checked = state;
        }
    }
</script>

