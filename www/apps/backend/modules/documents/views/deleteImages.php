<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<form id="form" method="post">
    <select class="target" name="packageIdRequested">
<?php
    foreach($packages as $package)
    {
        echo '<option ' . ($package->getId() == $packageIdRequested ? 'selected' : '') . ' value="' . $package->getId() . '">' . $package->getName('fr') . '</option>';
    }
?>
    </select>
</form>


<script type="text/javascript">
    $('.target').change(function() {
        $('#form').submit();
    });
</script>


<br/>


<?php
    function setSelected($data, $compareTo)
    {
        if ($data == $compareTo)
            return "selected='selected'";
        return '';
    }

    // Display archives
	for($i=0; $i<count($archives); ++$i)
	{
        if($archives[$i]->getIdPackage() != $packageIdRequested)
            continue;

        $idArchive = $archives[$i]->getId();

        echo '
        <table id="editableTable">
	        <tr>
		        <th>Nom</th>
		        <th>Action</th>
	        </tr>';

	        $sizeName = strlen($archives[$i]->getFilename());

	        echo '<tr>';
	        echo "<td><p  class='editable' 
                          data-id='{$archives[$i]->getId()}'
                          data-entry-name='ArchivesOfPackages'
                          data-field-name='Filename'
                          data-form-type='text'
                          data-form-size='{$sizeName}'>{$archives[$i]->getFilename()}</p></td>";
    		echo "<td><img class='deletable' data-entry-name='ArchivesOfPackages' data-id='$idArchive' src='../../web/images/delete.png' /></a></td>";
	        echo '</tr>';

        echo '</table>';

        echo '<br/><br/><br/><br/>';
    }
?>
