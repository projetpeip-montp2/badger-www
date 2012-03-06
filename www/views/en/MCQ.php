<form>
<?php
	for ($i = 1; $i < 5; ++$i)
	{
?>
	<label name="Test">Question <?php echo $i ?></label>
	<br/>
    <?php
	    for ($j = 1; $j < 6; ++$j)
	    {
    ?>
	    <input type="checkbox" value="<?php echo 'Answer' . $j ?>" name="Réponse <?php echo $j ?>" />Réponse <?php echo $j ?>
    <?php
	    }
	?>
	<br/>
<?php
	}
?>
</form>
