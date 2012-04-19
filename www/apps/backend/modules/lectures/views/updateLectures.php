<h1>Modification des conférences</h1>
<p>Depuis cette page, il est possible de modifier les conférences enregistrées dans la base de données.</p>
<?php
    $forms = array();
    foreach($lectures as $lecture)
    {
        $form = new Form('', 'post');

        $form->add('text', 'NameFr')
             ->value($lecture->getName('fr'))
             ->size(strlen($lecture->getName('fr')));
        $form->add('text', 'NameEn')
             ->value($lecture->getName('en'))
             ->size(strlen($lecture->getName('en')));
        $form->add('textarea', 'DescFr')
             ->text($lecture->getDescription('fr'));
        $form->add('textarea', 'DescEn')
             ->text($lecture->getDescription('en'));
        $form->add('text', 'Date')
             ->value($lecture->getDate())
             ->size(8);
        $form->add('text', 'StartTime')
             ->value($lecture->getStartTime())
             ->size(8);
        $form->add('text', 'EndTime')
             ->value($lecture->getEndTime())
             ->size(8);

        $form->add('hidden', 'lectureId')
             ->value($lecture->getId());
        $form->add('submit', 'Modifier');
        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>

<table class="FormTable">
    <th>Nom Fr</th>
    <th>Nom En</th>
    <th>Description Fr</th>
    <th>Description En</th>
    <th>Date</th>
    <th>Horaire de début</th>
    <th>Horaire de fin</th>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
