<form>
<?php
    global $questions;
	foreach ($questions as $q)
    {
        displayQuestion($q);
        echo "<br/>";
    }
?>
</form>
