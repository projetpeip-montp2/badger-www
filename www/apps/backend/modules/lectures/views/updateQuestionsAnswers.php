<h1>Modification des questions/réponses</h1>
<p>Depuis cette page, il est possible de modifier les questions/réponses enregistrés dans la base de données.</p>
<p>Le form juste ci-dessous permet de sélectionner un package.</p>
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
    // Display questions
	for($i=0; $i<count($questions); ++$i)
	{
        if($questions[$i]->getIdPackage() != $packageIdRequested)
            continue;

        echo'
        <table id="editableTable">
	        <tr>
		        <th>Label Fr</th>
		        <th>Label En</th>
		        <th>Status</th>
	        </tr>';

	        $sizeLabelFR = strlen($questions[$i]->getLabel('fr'));
	        $sizeLabelEN = strlen($questions[$i]->getLabel('en'));
	        $sizeStatus = strlen($questions[$i]->getStatus());

	        echo '<tr>';
	        echo "<td><p class='editable' data-id='{$questions[$i]->getId()}'  data-entry-name='Questions' data-field-name='Label_fr' data-form-type='text' data-form-size='{$sizeLabelFR}'>{$questions[$i]->getLabel('fr')}</p></td>";
	        echo "<td><p class='editable' data-id='{$questions[$i]->getId()}'  data-entry-name='Questions' data-field-name='Label_en' data-form-type='text' data-form-size='{$sizeLabelEN}'>{$questions[$i]->getLabel('en')}</p></td>";
	        echo "<td><p class='editable' data-id='{$questions[$i]->getId()}'  data-entry-name='Questions' data-field-name='Status' data-form-type='text' data-form-size='{$sizeStatus}'>{$questions[$i]->getStatus()}</p></td>";
	        echo '</tr>';

        echo '</table>';


        echo
        '<table id="editableTable">
	        <tr>
		        <th>Label Fr</th>
		        <th>Label En</th>
		        <th>True or False</th>
	        </tr>';

            // Display associated answers
        	for($j=0; $j<count($answers); ++$j)
	        {
                if($answers[$j]->getIdQuestion() != $questions[$i]->getId())
                    continue;

		        echo '<tr>';
		        echo "<td><p class='editable' data-id='{$answers[$j]->getId()}'  data-entry-name='Answers' data-field-name='Label_fr' data-form-type='text' data-form-size='{$sizeLabelFR}'>{$answers[$j]->getLabel('fr')}</p></td>";
		        echo "<td><p class='editable' data-id='{$answers[$j]->getId()}'  data-entry-name='Answers' data-field-name='Label_en' data-form-type='text' data-form-size='{$sizeLabelEN}'>{$answers[$j]->getLabel('en')}</p></td>";
		        echo "<td><p class='editable' data-id='{$answers[$j]->getId()}'  data-entry-name='Answers' data-field-name='TrueOrFalse' data-form-type='text' data-form-size='{$sizeStatus}'>{$answers[$j]->getTrueOrFalse()}</p></td>";
		        echo '</tr>'; 
	        }
        echo '</table>';

        echo '<br/><br/><br/><br/>';
    }
?>

