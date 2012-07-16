<p>Cette page permet de sélectionner un couple département/année, et d'affecter
tout les élèves de ce dernier à un package.</p>

<p>Un résumé est donné une fois l'inscription massive terminée.</p>

<p>Attention:</p>
<ul>
    <li>Seuls les élèves dont le nouveau package n'engendrera pas de problème
        dans les horaires seront inscris!</li>

    <li>S'il ne reste pas suffisament de places dans le packages, tous les
        élèves ne seront pas forcement inscris!</li>
</ul>

<?php
    $form = new Form('', 'post');

    $form->add('select', 'department')
           ->choices( $departmentChoices );

    $form->add('select', 'schoolYear')
         ->choices( array('3' => '3',
                          '4' => '4',
                          '5' => '5') );

    $form->add('select', 'idPackage')
         ->choices($packageChoices);

    $form->add('submit', 'Envoyer');
    $form->disableParagraphs();

    echo $form->toString();
?>
