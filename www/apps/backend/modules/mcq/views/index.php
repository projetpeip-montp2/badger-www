<ul>
    <li><a href="/admin/mcq/createMCQ.html">Inscrire une promotion</a></li>
    <li><a href="/admin/mcq/getMarks.html">Récupérer toutes les notes</a></li>
</ul>

<br/>
<br/>

<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<table id="editableTable">
	<tr>
		<th>Departement</th>
		<th>Année d'étude</th>
		<th>Date</th>
		<th>Horaire de début</th>
		<th>Horaire de fin</th>
		<th>Action</th>
	</tr>
<?php
	foreach ($mcqs as $mcq)
	{
        $id = $mcq->getId();
        
        $schoolYear = $mcq->getSchoolYear() + 2;

        $date = $mcq->getDate();
        $startTime = $mcq->getStartTime();
        $endTime = $mcq->getEndTime();

        echo '<tr>';

        echo "<td><p class='labelable' data-id='$id'  data-entry-name='MCQs' data-field-name='Department' data-form-type='textbox'>{$mcq->getDepartment()}</p></td>";

        echo "<td><p class='labelable' data-id='$id'  data-entry-name='MCQs' data-field-name='SchoolYear' data-form-type='textbox'>{$schoolYear}</p></td>";


        // Date
        echo '<td>';
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='Date' data-subfield-name='Day' data-form-type='number' data-form-size='2' data-id='$id' >{$date->day(TRUE)}</p>";
        echo "<p class='separator'>-</p>";
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='Date' data-subfield-name='Month' data-form-type='number' data-form-size='2' data-id='$id' >{$date->month(TRUE)}</p>";
        echo "<p class='separator'>-</p>";
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='Date' data-subfield-name='Year' data-form-type='number' data-form-size='2' data-id='$id' >{$date->year(TRUE)}</p>";
        echo '</td>';

        // StartTime
        echo '<td>';
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='StartTime' data-subfield-name='Hours' data-form-type='number' data-form-size='2' data-id='$id' >{$startTime->hours(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='StartTime' data-subfield-name='Minutes' data-form-type='number' data-form-size='2' data-id='$id' >{$startTime->minutes(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='StartTime' data-subfield-name='Seconds' data-form-type='number' data-form-size='2' data-id='$id' >{$startTime->seconds(TRUE)}</p>";
        echo '</td>';

        // EndTime
        echo '<td>';
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='EndTime' data-subfield-name='Hours' data-form-type='number' data-form-size='2' data-id='$id' >{$endTime->hours(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='EndTime' data-subfield-name='Minutes' data-form-type='number' data-form-size='2' data-id='$id' >{$endTime->minutes(TRUE)}</p>";
        echo "<p class='separator'>:</p>";
        echo "<p class='editable' data-entry-name='MCQs' data-field-name='EndTime' data-subfield-name='Seconds' data-form-type='number' data-form-size='2' data-id='$id' >{$endTime->seconds(TRUE)}</p> ";
        echo '</td>';

        // Action
        echo "<td><img src='../../web/images/delete.png' class='deletable' data-entry-name='MCQs' data-id='$id' /></p>";

        echo '</tr>';
	}
?>
</table>
