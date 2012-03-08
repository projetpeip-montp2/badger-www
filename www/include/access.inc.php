<?php
    $validUsers = array(
					    'vbmifare',
                        'gregoire.guisez',
                        'jamal.hennani',
                        'victor.hiairrassary',
                        'william.tassoux'
					    );

    function isValidUser($userName)
    {
        global $validUsers;

        return (in_array($userName, $validUsers) === TRUE);
    }
?>
