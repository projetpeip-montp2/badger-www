<?php

/*
Input/Output security functions
*/

function secureInputData($string)
{
	if(ctype_digit($string))
	{
		$string = intval($string);
	}
	else
	{
		//$string = mysql_real_escape_string($string);
		$string = addcslashes($string, '%_');
	}
	return $string;
}

function secureOutputData($string)
{
	return htmlentities($string);
}

function includeView($pageName)
{
    require_once("views/" . $_SESSION['lang'] . "/" . $pageName . ".php");
}

function isValidPage($pageName)
{
        global $validPages;

	    return (is_file("controllers/" . $pageName . ".php") && in_array($pageName, $validPages) === TRUE) ? true : false;
}

?>
