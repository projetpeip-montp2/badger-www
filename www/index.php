<?php	
	// Include of configurations
	require_once("include/config.inc.php");

	// Include of required libs
	require_once("include/security.inc.php");

	// Language definition
	require_once("include/lang.inc.php");

	require_once("include/header.inc.php");
	require_once("include/menu.inc.php");
?>

	<div id="Body">
		<?php
			if (!empty($_GET['page']))
			{
				$pageName = secureInputData($_GET['page']);
				if (is_file("controllers/" . $pageName . ".php"))
					include("controllers/" . $pageName . ".php");
			}
			else
				require_once("controllers/home.php");
		?>
	</div>

<?php
	require_once("include/footer.inc.php");
?>

