<h1>Modification des salles</h1>
<p>Depuis cette page, il est possible de modifier les disponibilités de salles enregistrées dans la base de données.</p>
<?php
    $forms = array();
    foreach($availabilities as $availability)
    {
        $form = new Form('', 'post');

        $form->add('text', 'Date')
             ->value($availability->getDate())
             ->size(8);
        $form->add('text', 'StartTime')
             ->value($availability->getStartTime())
             ->size(8);
        $form->add('text', 'EndTime')
             ->value($availability->getEndTime())
             ->size(8);

        $form->add('hidden', 'availabilityId')
             ->value($availability->getId());
        $form->add('submit', 'Modifier');

        $forms[] = $form;
    }

    echo '<table class="FormTable">';
    foreach($forms as $form)
        echo $form->toTr();
    echo '</table>';
?>
