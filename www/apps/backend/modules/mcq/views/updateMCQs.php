<h1>Modification/Suppression d'une inscription de promotions</h1>
<p class="Warning">
    L'inscription ne fonctionne que pour les départements ingénieurs (MAT, ERII, etc), pas pour les PEIP (et les autres). (Problème dans la gestion des années)
</p>
<p class="Info">
    Date au format "JJ-MM-AAAA" et heures au format "HH:MM:SS".
</p>
<?php
    $schoolYears = array('3' => '3','4' => '4', '5' => '5');

    $forms = array();
    foreach($mcqs as $mcq)
    {
        $form = new Form('', 'post');

        $form->add('select', 'Department')
             ->choices($departments)
             ->selected($mcq->getDepartment());
        $form->add('select', 'SchoolYear')
             ->choices($schoolYears)
             ->selected($mcq->getSchoolYear());
        $form->add('text', 'Date')
             ->value($mcq->getDate())
             ->size(8);
        $form->add('text', 'StartTime')
             ->value($mcq->getStartTime())
             ->size(8);
        $form->add('text', 'EndTime')
             ->value($mcq->getEndTime())
             ->size(8);
        $form->add('submit', 'Modifier');
        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }

?>

<table class="FormTable">
    <tr>
        <th>Département</th>
        <th>Année</th>
        <th>Date</th>
        <th>Horaire de début</th>
        <th>Horaire de fin</th>
    </tr>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
