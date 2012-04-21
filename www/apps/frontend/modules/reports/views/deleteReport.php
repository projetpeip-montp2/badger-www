<h1><?php echo $TEXT['Reports_Delete']; ?></h1>

<?php
    $forms = array();
    foreach($reports as $report)
    {
        $form = new Form('', 'post');

        // Get packages' name
        foreach($packages as $package)
        {
            if($package->getId() == $report->getIdPackage())
            {
                $form->add('label', 'Package')
                     ->label($package->getName('fr'));
                $form->add('hidden', 'PackageName')
                     ->value($package->getName('fr'));
                $form->add('hidden', 'packageId')
                     ->value($package->getId());
            // TODO: Debug form
            }
        }
        $form->add('label', 'Name')
             ->label($report->getFilename());
        $form->add('hidden', 'reportName')
             ->value($report->getFilename());

        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>

<table class="FormTable">
    <tr>
        <th>Package</th>
        <th><?php echo $TEXT['Reports_Filename']; ?></th>
    </tr>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
