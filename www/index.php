<?php	
	// Include of configurations
	require_once("include/config.inc.php");

	// Include of required libs
	require_once("include/functions.inc.php");

	// Include of required classes
    // TODO: Faire un script qui charge automatiquement les classes utilisées
	require_once("lib/Lecture.class.php");
	require_once("lib/Question.class.php");

	// Include valids users
	require_once("include/access.inc.php");

	session_start();
    // TODO: Remove the second condition when the website is finished
    if (!isset($_SESSION['logon']) || !isValidUser($_SESSION['logon']))
        header('Location: http://www.polytech.univ-montp2.fr/intra/');
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
