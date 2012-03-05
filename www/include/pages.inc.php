<?php
    $validsPages = array(
					    'home'
					    );


    function isValidPage($pageName)
    {
	    return (is_file("controllers/" . $pageName . ".php") && in_array(pageName, $validsPages) === TRUE) ? true : false;
    }
?>
