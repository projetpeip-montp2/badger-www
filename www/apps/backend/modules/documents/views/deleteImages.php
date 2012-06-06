<?php
    $forms = array();

    // There is at least one element in images (checked in Controller)
    $i = 1;

    // Loop on every images
    while($i < count($images))
    {
        // Count how many images there are for each packages
        $counter = 0;
        while($i < count($images) && ($images[$i - 1]->getIdPackage() == $images[$i]->getIdPackage()))
        {
            $counter++;
            $i++;
        }
        $counter++;

        $form = new Form('', 'post');

        // Get packages' name
        foreach($packages as $package)
        {
            if($package->getId() == $images[$i - 1]->getIdPackage())
            {
                $form->add('label', 'Package')
                     ->label($package->getName('fr'));
                $form->add('hidden', 'PackageName')
                     ->value($package->getName('fr'));
                $form->add('hidden', 'packageId')
                     ->value($package->getId());
            }
        }

        $form->add('label', 'Count')
             ->label($counter);

        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>

<table class="FormTable">
    <tr>
        <th>Nom du package</th>
        <th>Nombre d'images associées</th>
    </tr>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
