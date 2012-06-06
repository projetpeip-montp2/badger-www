<script src="../../web/js/jquery-1.7.1.min.js"></script>

<script type="text/javascript">
	var jsonPackages = <?php echo json_encode($packages); ?>;
	var jsonClassrooms = <?php echo json_encode($classrooms); ?>;

</script>
<script type="text/javascript" src="../../web/js/lecturesManager.js">
</script>

<?php
	foreach ($packages as $package)
	{
		echo "<p class='packageTitle'>Package: {$package['name']}</p>";
		echo "<table id='editableTable'>";
		echo "<tr><th>Nom de la conférence</th><th>Date</th><th>Heure de début</th><th>Heure de fin</th><th>Salle</th></tr>";
		
		foreach ($package['lectures'] as $lecture)
		{
			echo "<tr class='trLecture' data-id-package='{$package['id']}' data-id='{$lecture['id']}' data-id-availability='{$lecture['idAvailability']}'><td>{$lecture['name']}</td><td>{$lecture['date']}</td><td>{$lecture['startTime']}</td><td>{$lecture['endTime']}</td><td><select></select></td></tr>\r\n";
		}
	
		echo "</table>";
	}
?>
