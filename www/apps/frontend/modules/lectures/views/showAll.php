<p>Liste des conférences</p>

<?php
    $lang;

    echo '<ul>';

    foreach($lectures as $lecture)
    {
        $methodName = 'getName'.ucfirst($lang);
        $methodDescription = 'getDescription'.ucfirst($lang);
?>
        <li>
            <h1><?php echo $lecture->$methodName(); ?></h1>
            <p class="lectureDescription"><?php echo $lecture->$methodDescription(); ?></p>
        </li>
<?php
    }

    echo '</ul>';
?>
