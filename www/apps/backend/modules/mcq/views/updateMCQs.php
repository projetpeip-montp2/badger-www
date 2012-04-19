<h1>Modification d'une séance de QCM</h1>
<p>Depuis cette page, il est possible de modifier une séance de QCM pour une promotion.</p>
<p>Rappel: Date au format "JJ-MM-AAAA" et heures au format "HH:MM:SS".</p>
<?php
    // TODO: Trouver une meilleure manière de faire
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

        $forms[] = $form;
    }

?>

<table class="FormTable">
    <th>Département</th>
    <th>Année</th>
    <th>Date</th>
    <th>Horaire de début</th>
    <th>Horaire de fin</th>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
