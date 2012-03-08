<?php
    $validPages = array(
					    'home',
                        'guide',
                        'lectures',
                        'MCQ'
					    );

    function isValidPage($pageName)
    {
            global $validPages;

	        return (is_file("controllers/" . $pageName . ".php") && in_array($pageName, $validPages) === TRUE);
    }

?>
