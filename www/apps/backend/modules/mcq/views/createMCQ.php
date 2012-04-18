<h1>Création d'une séance de QCM</h1>
<p>Depuis cette page, il est possible de créer une séance de QCM pour une promotion.</p>
<p>Rappel: Date au format "JJ-MM-AAAA" et heures au format "HH:MM:SS".</p>
<?php
    $form = new Form('', 'post');

    // TODO: Trouver une meilleure manière de faire
    $schoolYears = array('3' => '3','4' => '4', '5' => '5');

    $form->add('select', 'Department')
         ->label('Département')
         ->choices($departments);
    $form->add('select', 'SchoolYear')
         ->label('Année')
         ->choices($schoolYears);
    $form->add('text', 'Date')
         ->label('Date : ')
         ->value('JJ-MM-AA')
         ->size(8);
    $form->add('text', 'StartTime')
         ->label('Horaire de début : ')
         ->value('HH:MM:SS')
         ->size(8);
    $form->add('text', 'EndTime')
         ->label('Horaire de fin : ')
         ->value('HH:MM:SS')
         ->size(8);

    $form->add('hidden', 'isSubmitted')
         ->value('on');
    $form->add('submit', 'Envoyer');

    echo $form->toString();
?>
