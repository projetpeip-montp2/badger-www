<h1>Modification des salles</h1>
<p>Depuis cette page, il est possible de modifier les disponibilités de salles enregistrées dans la base de données.</p>
<?php
    $forms = array();
    foreach($availabilities as $availability)
    {
        $form = new Form('', 'post');

        // Get classrooms' name
        foreach($classrooms as $classroom)
        {
            if($classroom->getId() == $availability->getIdClassroom())
            {
                $form->add('label', 'Classroom')
                     ->label($classroom->getName());
                $form->add('hidden', 'ClassroomName')
                     ->value($classroom->getName());
            }
        }

        $form->add('text', 'Date')
             ->value($availability->getDate())
             ->size(10);
        $form->add('text', 'StartTime')
             ->value($availability->getStartTime())
             ->size(8);
        $form->add('text', 'EndTime')
             ->value($availability->getEndTime())
             ->size(8);

        $form->add('hidden', 'availabilityId')
             ->value($availability->getId());

        $form->add('submit', 'Modifier');
        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>

<table class="FormTable">
    <tr>
        <th>Salle</th>
        <th>Date</th>
        <th>Horaire de début</th>
        <th>Horaire de fin</th>
    </tr>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
