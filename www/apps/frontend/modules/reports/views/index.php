<ul>
    <li><a href="/reports/upload.html"><?php echo $TEXT['Reports_Upload']; ?></a></li>
</ul>


<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<table id="editableTable">
	<tr>
		<th>Nom du rapport</th>
		<th>Action</th>
	</tr>

<?php
	foreach ($reports as $report)
	{
		$sizeReportName = strlen($report->getFilename());

		echo '<tr>';
		echo "<td><p class='labelable' data-form-size='{$sizeReportName}'>{$report->getFilename()}</p></td>";

		echo "<td><img class='deletable' data-entry-name='DocumentsOfUsers' data-id='{$report->getId()}' src='../../web/images/delete.png' /></a></td>";
		echo '</tr>';
	}
?>
</table>
