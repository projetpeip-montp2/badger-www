<?php
	// Initialization
	//$configuration = parse_ini_file("conf.ini");
	require_once("include/functions.inc.php");
	session_start();
	if (!isset($_SESSION['lang']))
		$_SESSION['lang'] = "fr";
	require_once("lang/" . $_SESSION['lang'] .".php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $TEXT['Lang']; ?>" lang="<?php echo $TEXT['Lang']; ?>">
	<head>
		 <title><?php echo $TEXT['Page_Title']; ?></title>
		 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 <meta http-equiv="Content-Language" content="<?php echo $TEXT['Lang']; ?>" />
		 <meta http-equiv="Content-Script-Type" content="text/javascript" />
		 <link href="style.css" rel="stylesheet" type="text/css" />
	</head>

	<body>
	<?php	
		require_once("include/header.inc.php");
		require_once("include/menu.inc.php");
	?>

		<div id="Body">
			<?php
				if (!empty($_GET['page']))
				{
					$pageName = secureInputData($_GET['page']);
					if (is_file("controllers/" . $pageName . ".php"))
						require_once("controllers/" . $pageName . ".php");
					else
						require_once("controllers/home.php");
				}
				else
					require_once("controllers/home.php");
			?>
		</div>

	<?php
		require_once("include/footer.inc.php");
	?>
	</body>
</html/>
