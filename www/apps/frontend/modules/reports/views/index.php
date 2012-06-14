<ul>
    <li><a href="/reports/upload.html"><?php echo $TEXT['Reports_Upload']; ?></a></li>
</ul>


<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<table id="editableTable">
	<tr>
		<th><?php echo $TEXT['Reports_Name']; ?></th>
		<th><?php echo $TEXT['Reports_Action']; ?></th>
	</tr>

<?php
	foreach ($reports as $report)
	{
		$sizeReportName = strlen($report->getFilename());

		echo '<tr>';
		echo "<td><p class='labelable' data-form-size='{$sizeReportName}'>{$report->getFilename()}</p></td>";

		echo "<td><img class='deletable' data-app-name='frontend' data-entry-name='DocumentsOfUsers' data-id='{$report->getId()}' src='../../web/images/delete.png' /></a></td>";
		echo '</tr>';
	}
?>
</table>
