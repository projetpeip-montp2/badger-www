<p>
    Permettre de repasser le QCM à une promotion:
</p>
<?php
    $form = new Form('', 'post');

    $form->add('select', 'department')
           ->choices( $departmentChoices );

    $form->add('select', 'schoolYear')
         ->choices( array('1' => '3',
                          '2' => '4',
                          '3' => '5') );

    $form->add('submit', 'Envoyer');
    $form->disableParagraphs();

    echo $form->toString();
?>
