<?php
    $forms = array();
    foreach($packages as $package)
    {
        $form = new Form('', 'post');

        $form->add('text', 'Lecturer')
             ->value($package->getLecturer());
        $form->add('text', 'NameFr')
             ->value($package->getNameFr());
        $form->add('text', 'NameEn')
             ->value($package->getNameEn());
        $form->add('textarea', 'DescFr')
             ->text($package->getDescriptionFr());
        $form->add('textarea', 'DescEn')
             ->text($package->getDescriptionEn());
        $form->add('submit', 'Modifier');
        $forms[] = $form;
    }

    echo '<table class="FormTable">';
    foreach($forms as $form)
        echo $form->toTr();
    echo '</table>';
?>
