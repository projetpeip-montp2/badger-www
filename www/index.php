<?php	
	// Include of configurations
	require_once("include/config.inc.php");

	// Include of required libs
	require_once("include/security.inc.php");

	// Include valids pages
	require_once("include/pages.inc.php");

	// Language definition
	require_once("include/lang.inc.php");

	require_once("include/header.inc.php");
	require_once("include/menu.inc.php");
?>

	<div id="Body">
		<?php
            $pageName = $configurations['default_page'];

			if (isset($_GET['page']) && !empty($_GET['page']))
			{
				$pageName = secureInputData($_GET['page']);

                if(!isValidPage($pageName))
                    $pageName = $configurations['default_page'];
			}

			include("controllers/" . $pageName . ".php");
		?>
	</div>

<?php
	require_once("include/footer.inc.php");
?>

