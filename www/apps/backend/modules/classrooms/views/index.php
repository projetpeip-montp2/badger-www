<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<h1>Gestion des salles</h1>

<ul>
    <li><a href="/vbMifare/admin/classrooms/addClassrooms.html">Uploader des salles</a></li>
    <li><a href="/vbMifare/admin/classrooms/addAvailabilities.html">Uploader des disponibilités</a></li>

	<li><a class="addable" data-entry-name='Classrooms' style="font-size: 16px">Ajouter une salle</a></li>
<!--
    <li><a href="/vbMifare/admin/classrooms/updateClassrooms.html">Modifier les salles</a></li>
    <li><a href="/vbMifare/admin/classrooms/updateAvailabilities.html">Modifier les disponibilités</a></li>
-->
</ul>

<table id="editableTable">
	<tr>
		<th>Nom</th>
		<th>Contenance</th>
		<th>Disponibilités</th>
		<th>Action</th>
	</tr>
<?php
	function hasAvailabilities($availabilities)
	{
		return (!(count($availabilities) == 1 && $availabilities[0]->getDate()->year() == 2000));
	}
	
	function getNumberWithZero($type)
	{
		return (str_pad($number, 2, '0', STR_PAD_LEFT));
	}
	
	foreach ($classrooms as $classroom)
	{
		$idClassroom = $classroom->getId();
		$name = $classroom->getName();
		$size = $classroom->getSize();
		
		echo "<tr>";
		echo "<td class='editable' data-entry-name='Classrooms' data-field-name='Name' data-form-type='text' data-id='$idClassroom'>$name</td>";
		echo "<td class='editable' data-entry-name='Classrooms' data-field-name='Size' data-form-type='number' data-id='$idClassroom'>$size</td>";
		echo "<td>";
		if (hasAvailabilities($classroom->getAvailabilities()))
			foreach ($classroom->getAvailabilities() as $availability)
				{
					$id = $availability->getId();
					$date = $availability->getDate();
					$startTime = $availability->getStartTime();
					$endTime = $availability->getEndTime();
				
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='Date' data-subfield-name='Day' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$date->day(TRUE)}</p>-";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='Date' data-subfield-name='Month' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$date->month(TRUE)}</p>-";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='Date' data-subfield-name='Year' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$date->year(TRUE)}</p> | ";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='StartTime' data-subfield-name='Hours' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$startTime->hours(TRUE)}</p>:";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='StartTime' data-subfield-name='Minutes' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$startTime->minutes(TRUE)}</p>:";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='StartTime' data-subfield-name='Seconds' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$startTime->seconds(TRUE)}</p> -> ";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='EndTime' data-subfield-name='Hours' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$endTime->hours(TRUE)}</p>:";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='EndTime' data-subfield-name='Minutes' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$endTime->minutes(TRUE)}</p>:";
					echo "<p class='editable' data-entry-name='Availabilities' data-field-name='EndTime' data-subfield-name='Seconds' data-form-type='number' data-form-size='2' data-id='$id' data-id-sub='$idClassroom'>{$endTime->seconds(TRUE)}</p><br />";

				}
		echo "<a class='addable' data-entry-name='Availabilities' data-id='$idClassroom'>Insérer une nouvelle disponibilité</a>";
		echo "</td>";
		echo "<td><img class='deletable' data-entry-name='Classrooms' data-id='$idClassroom' src='../../web/images/delete.png' /></a></td>";
		echo "</tr>";
	}
?>
</table>
