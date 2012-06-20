<ul>
    <li><a href="/admin/lectures/assignLectures.html">Assigner les salles aux conférences</a></li>
    <li><a href="/admin/lectures/addBadgingInformation.html">Ajouter la présence d'un élève à une conférence</a></li>
</ul>


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

<p>Format des fichiers CSV:
<br/>
"Nom conférencier","Nom fr","Nom en","Description fr","Description en","Date","Heure de début","Heure de fin"
</p>

<p>Ajouter des conférences aux conférences existantes<p>
<?php
    $form = new Form('/admin/lectures/addLectures.html', 'post');

    $form->add('file', 'CSVFile')
         ->isInParagraph(false);

    $form->add('hidden', 'idPackage');

    $form->add('submit', 'Envoyer')
         ->isInParagraph(false);

    echo $form->toString();
?>

<script type="text/javascript">
    $(document).ready(function() {
      $('input[name="idPackage"]').attr(
                                      'value',
                                       $('.target').val()
                                       );
    });
</script>


<br/>
<br/>



<table id="editableTable">
    <caption>Tableau d'édition des lectures</caption>

	<tr>
		<th>Conférencier</th>
		<th>Nom Fr</th>
		<th>Nom En</th>
		<th>Description Fr</th>
		<th>Description En</th>
		<th>Date</th>
		<th>Heure de début</th>
		<th>Heure de fin</th>
		<th>Action</th>
	</tr>

<?php
    foreach ($lectures as $lecture)
    {
        $id = $lecture->getId();
        $date = $lecture->getDate();
        $startTime = $lecture->getStartTime();
        $endTime = $lecture->getEndTime();

        echo '<tr>';

        echo "<td><p class='editable' data-id='$id'  data-entry-name='Lectures' data-field-name='Lecturer' data-form-type='textbox'>{$lecture->getLecturer()}</p></td>";

        echo "<td><p class='editable' data-id='$id'  data-entry-name='Lectures' data-field-name='Name_fr' data-form-type='textbox'>{$lecture->getName('fr')}</p></td>";

        echo "<td><p class='editable' data-id='$id'  data-entry-name='Lectures' data-field-name='Name_en' data-form-type='textbox'>{$lecture->getName('en')}</p></td>";

        echo "<td><p class='editable' data-id='$id'  data-entry-name='Lectures' data-field-name='Description_fr' data-form-type='textbox'>{$lecture->getDescription('fr')}</p></td>";

        echo "<td><p class='editable' data-id='$id'  data-entry-name='Lectures' data-field-name='Description_en' data-form-type='textbox'>{$lecture->getDescription('en')}</p></td>";


        // Date
        echo '<td>';
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='Date' data-subfield-name='Day' data-form-type='number' data-form-size='2' data-id='$id' >{$date->day(TRUE)}</p>";
        echo "<p class='separator'>-</p>";
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='Date' data-subfield-name='Month' data-form-type='number' data-form-size='2' data-id='$id' >{$date->month(TRUE)}</p>";
        echo "<p class='separator'>-</p>";
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='Date' data-subfield-name='Year' data-form-type='number' data-form-size='2' data-id='$id' >{$date->year(TRUE)}</p>";
        echo '</td>';

        // StartTime
        echo '<td>';
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='StartTime' data-subfield-name='Hours' data-form-type='number' data-form-size='2' data-id='$id' >{$startTime->hours(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='StartTime' data-subfield-name='Minutes' data-form-type='number' data-form-size='2' data-id='$id' >{$startTime->minutes(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='StartTime' data-subfield-name='Seconds' data-form-type='number' data-form-size='2' data-id='$id' >{$startTime->seconds(TRUE)}</p>";
        echo '</td>';

        // EndTime
        echo '<td>';
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='EndTime' data-subfield-name='Hours' data-form-type='number' data-form-size='2' data-id='$id' >{$endTime->hours(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='EndTime' data-subfield-name='Minutes' data-form-type='number' data-form-size='2' data-id='$id' >{$endTime->minutes(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='Lectures' data-field-name='EndTime' data-subfield-name='Seconds' data-form-type='number' data-form-size='2' data-id='$id' >{$endTime->seconds(TRUE)}</p> ";
        echo '</td>';

        // Action
        echo "<td><img src='../../web/images/delete.png' class='deletable' data-entry-name='Lectures' data-id='$id' /></p>";

        echo '</tr>';
    }
?>
</table>
