<?php
    $defaultValue = $configurations["default_lang"];

	if ( isset($_GET["lang"]) )
	{
		$lang = secureInputData($_GET["lang"]);

		if ($lang == "en")
			require_once("lang/en.php");

		elseif ($lang == "fr")
			require_once("lang/fr.php");

		// To use another language, use following commented code
		//
		// elseif ($lang == "template")
		//     require_once("lang/template.php");

		else
			require_once("lang/".$defaultValue.".php");
	}
	else
		require_once("lang/".$defaultValue.".php");
?>
