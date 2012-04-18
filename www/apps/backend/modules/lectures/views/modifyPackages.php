<h1>Modification des packages</h1>
<p>Depuis cette page, il est possible de modifier les packages enregistrés dans la base de données.</p>
<?php
    $forms = array();
    foreach($packages as $package)
    {
        $form = new Form('', 'post');

        $form->add('text', 'Lecturer')
             ->value($package->getLecturer());
        $form->add('text', 'NameFr')
             ->value($package->getName('fr'));
        $form->add('text', 'NameEn')
             ->value($package->getName('en'));
        $form->add('textarea', 'DescFr')
             ->text($package->getDescription('fr'));
        $form->add('textarea', 'DescEn')
             ->text($package->getDescription('en'));

        $form->add('hidden', 'packageId')
             ->value($package->getId());
        $form->add('submit', 'Modifier');

        $forms[] = $form;
    }

    echo '<table class="FormTable">';
    foreach($forms as $form)
        echo $form->toTr();
    echo '</table>';
?>
