<?php	
	// Include of configurations
	require_once("include/config.inc.php");

	// Include of required libs
	require_once("include/functions.inc.php");

    // Language definition
	session_start();
	if (!isset($_SESSION['lang']))
		$_SESSION['lang'] = "fr";
	require_once("lang/" . $_SESSION['lang'] .".php");

	// Include valids pages
	require_once("include/pages.inc.php");

    // Header and menu
	require_once("views/header.php");
	require_once("views/menu.php");
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
            $_SESSION['page'] = $pageName;

			include("controllers/" . $pageName . ".php");
		?>
	</div>

<?php
    // Footer
	require_once("views/footer.php");
?>
