<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<br/>
<form id="form" method="post">
    Sélection du package: <select class="target" name="packageIdRequested">
<?php
    foreach($packages as $package)
    {
        echo '<option ' . ($package->getId() == $packageIdRequested ? 'selected' : '') . ' value="' . $package->getId() . '">' . $package->getName('fr') . '</option>';
    }
?>
    </select>
</form>

<?php
    echo '<br/><br/><div style="border-top: 1px solid black"></div><br/>';
?>


<script type="text/javascript">
    // Submit form when select is changed
    $('.target').change(function() {

        $('#form').submit();
    });

    $(document).ready(function() {
      $('input[name="idPackage"]').attr(
                                      'value',
                                       $('.target').val()
                                       );
    });
    
</script>

<?php
    function setSelected($data, $compareTo)
    {
        if ($data == $compareTo)
            return "selected='selected'";
        return '';
    }

    echo '<h1>Archives zip</h1>';

    // Upload new archive
    $form = new Form('', 'post');

    $form->add('text', 'filename')
         ->label('Nom du fichier: ');

    $form->add('hidden', 'idPackage');

    $form->add('file', 'zipFile')
         ->label('Chemin du fichier zip : ');

    $form->add('submit', 'Envoyer');

    echo $form->toString();

    echo '
    <table id="editableTable">
        <tr>
	        <th>Nom</th>
	        <th>Action</th>
        </tr>';

    // Display uploaded archives
	for($i=0; $i<count($archives); ++$i)
	{
        if($archives[$i]->getIdPackage() != $packageIdRequested)
            continue;

        $idArchive = $archives[$i]->getId();


        $sizeName = strlen($archives[$i]->getFilename());

        echo '<tr>';
        echo "<td><p  class='editable' 
                      data-id='{$archives[$i]->getId()}'
                      data-entry-name='ArchivesOfPackages'
                      data-field-name='Filename'
                      data-form-type='text'
                      data-form-size='{$sizeName}'>{$archives[$i]->getFilename()}</p></td>";
		echo "<td><img class='deletable' data-entry-name='ArchivesOfPackages' data-id='$idArchive' src='../../web/images/delete.png'/></td>";
        echo '</tr>';
    }
    echo '</table>';

    echo '<br/><br/><div style="border-top: 1px solid black"></div><br/>';

    echo '<h1>Documents</h1>';

    // Upload new document
    $form = new Form('', 'post');

    $form->add('file', 'PDFFile')
         ->label('Chemin du fichier PDF: ');

    $form->add('hidden', 'idPackage');

    $form->add('submit', 'Envoyer');

    echo $form->toString();

    echo '
    <table id="editableTable">
        <tr>
	        <th>Nom</th>
	        <th>Action</th>
        </tr>';

    // Display uploaded documents
	for($i=0; $i<count($documents); ++$i)
	{
        if($documents[$i]->getIdPackage() != $packageIdRequested)
            continue;

        $idDocument = $documents[$i]->getId();

	        $sizeName = strlen($documents[$i]->getFilename());

        echo '<tr>';
        echo "<td><p  class='labelable' data-form-size='{$sizeName}'>{$documents[$i]->getFilename()}</p></td>";
		echo "<td><img class='deletable' 
                       data-entry-name='DocumentsOfPackages'
                       data-id='$idDocument'
                       src='../../web/images/delete.png' /></td>";
        echo '</tr>';
    }
    echo '</table>';
    echo '<br/>';

?>
