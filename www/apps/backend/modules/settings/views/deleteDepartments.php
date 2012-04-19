<h1>Changement du status des inscriptions</h1>
<p>Depuis cette page, on peut modifier les départements enregistrés dans la base de données.</p>

<?php
    $forms = array();
    foreach($departments as $department)
    {
        $form = new Form('', 'post');
        $form->add('label', 'Department')
             ->label($department);
        $form->add('hidden', 'DepartmentName')
             ->value($department);

        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>

<table class="FormTable">
    <th>Département</th>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
