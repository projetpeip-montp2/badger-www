<?php
    $forms = array();
    foreach($documents as $document)
    {
        $form = new Form('', 'post');

        // Get packages' name
        foreach($packages as $package)
        {
            if($package->getId() == $document->getIdPackage())
            {
                $form->add('label', 'Package')
                     ->label($package->getName('fr'));
                $form->add('hidden', 'PackageName')
                     ->value($package->getName('fr'));
            }
        }
        $form->add('label', 'Name')
             ->label($document->getFilename());
        $form->add('hidden', 'DocumentName')
             ->value($document->getFilename());
        $form->add('hidden', 'documentId')
             ->value($document->getId());

        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>

<table class="FormTable">
    <tr>
        <th>Nom du package</th>
        <th>Nom du document</th>
    </tr>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>
