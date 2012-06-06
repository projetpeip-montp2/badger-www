<p class="warning">
    La promotion doit être inscrite pour pouvoir s'inscrire à des packages et passer le QCM.
    <br/>
    <br/>
    Les dates peuvent être inexactes à la création, vous pouvez les modifier depuis la page d'édition.
    <br/>
    <br/>
    Ne pas hésiter à mettre des dates "lointaines".
    <br/>
    <br/>
    L'inscription ne fonctionne que pour les départements ingénieurs (MAT, ERII, etc), pas pour les PEIP (et les autres). (Problème dans la gestion des années)
</p>
<p class="info">
    Date au format "JJ-MM-AAAA" et heures au format "HH:MM:SS".
</p>
<?php
    $form = new Form('', 'post');

    $schoolYears = array('3' => '3','4' => '4', '5' => '5');

    $form->add('select', 'Department')
         ->label('Département')
         ->choices($departments);
    $form->add('select', 'SchoolYear')
         ->label('Année')
         ->choices($schoolYears);
    $form->add('text', 'Date')
         ->label('Date : ')
         ->value('JJ-MM-AAAA')
         ->size(10);
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
