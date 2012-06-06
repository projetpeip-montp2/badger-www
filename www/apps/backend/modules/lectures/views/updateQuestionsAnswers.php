<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>
<script src="../../web/js/questionsManagers.js"></script>


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

    // Display questions
	for($i=0; $i<count($questions); ++$i)
	{
        if($questions[$i]->getIdPackage() != $packageIdRequested)
            continue;

        $idQuestion = $questions[$i]->getId();

        echo'
        <table id="editableTable">
	        <tr>
		        <th>Label Fr</th>
		        <th>Label En</th>
		        <th>Status</th>
		        <th>Action</th>
	        </tr>';

	        $sizeLabelFR = strlen($questions[$i]->getLabel('fr'));
	        $sizeLabelEN = strlen($questions[$i]->getLabel('en'));
	        $sizeStatus = strlen($questions[$i]->getStatus());

            $select = "<option " . setSelected($questions[$i]->getStatus(), 'Possible') . " value='Possible'>Possible</option><option " . setSelected($questions[$i]->getStatus(), 'Impossible') . " value='Impossible'>Impossible</option><option " . setSelected($questions[$i]->getStatus(), 'Obligatory') . " value='Obligatory'>Obligatoire</option>";

	        echo '<tr>';
	        echo "<td><p class='editable' data-id='{$questions[$i]->getId()}'  data-entry-name='Questions' data-field-name='Label_fr' data-form-type='text' data-form-size='{$sizeLabelFR}'>{$questions[$i]->getLabel('fr')}</p></td>";
	        echo "<td><p class='editable' data-id='{$questions[$i]->getId()}'  data-entry-name='Questions' data-field-name='Label_en' data-form-type='text' data-form-size='{$sizeLabelEN}'>{$questions[$i]->getLabel('en')}</p></td>";
	        echo "<td><select class='selectable' data-id='{$questions[$i]->getId()}'  data-entry-name='Questions' data-field-name='Status' data-form-size='{$sizeStatus}'>{$select}</select></td>";
    		echo "<td><img class='deletable' data-entry-name='Questions' data-id='$idQuestion' src='../../web/images/delete.png' /></a></td>";
	        echo '</tr>';

        echo '</table>';


        echo
        '<table id="editableTable">
	        <tr>
		        <th>Label Fr</th>
		        <th>Label En</th>
		        <th>True or False</th>
		        <th>Action</th>
	        </tr>';

            // Display associated answers
        	for($j=0; $j<count($answers); ++$j)
	        {
                if($answers[$j]->getIdQuestion() != $questions[$i]->getId())
                    continue;

                $idAnswer = $answers[$j]->getId();
                    
                if ($answers[$j]->getTrueOrFalse() == 'T')
                    $select = "<option selected='selected' value='T'>Vrai</option><option value='F'>Faux</option>";
                else
                    $select = "<option value='T'>Vrai</option><option selected='selected' value='F'>Faux</option>";
		        echo '<tr>';
		        echo "<td><p class='editable' data-id='{$answers[$j]->getId()}'  data-entry-name='Answers' data-field-name='Label_fr' data-form-type='text' data-form-size='{$sizeLabelFR}'>{$answers[$j]->getLabel('fr')}</p></td>";
		        echo "<td><p class='editable' data-id='{$answers[$j]->getId()}'  data-entry-name='Answers' data-field-name='Label_en' data-form-type='text' data-form-size='{$sizeLabelEN}'>{$answers[$j]->getLabel('en')}</p></td>";
		        echo "<td><select class='selectable' data-entry-name='Answers' data-field-name='TrueOrFalse' data-id='{$answers[$j]->getId()}' name='TrueOrFalse'>{$select}</select></p></td>";
        		echo "<td><img class='deletable' data-entry-name='Answers' data-id='$idAnswer' src='../../web/images/delete.png' /></a></td>";
		        echo '</tr>'; 
	        }
        echo '</table>';

        echo '<br/><br/><br/><br/>';
    }
?>

