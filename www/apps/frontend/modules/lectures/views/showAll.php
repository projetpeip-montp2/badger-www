<p>Liste des conférences</p>

<?php
    $lang;

    foreach($lectures as $lecture)
    {
        $methodName = 'getName'.ucfirst($lang);
        $methodDescription = 'getDescription'.ucfirst($lang);
?>
        <p>
            <h> <?php echo $lecture->$methodName(); ?> </h>
            <p class="lectureDescription"><?php echo $lecture->$methodDescription(); ?></p>
        </p>
        <br />
<?php
    }
?>
