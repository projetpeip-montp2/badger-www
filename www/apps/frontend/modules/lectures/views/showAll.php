<p><?php echo $TEXT['Lecture_ListOf']; ?></p>

<?php
    $lang;

    echo '<ul>';

    foreach($lectures as $lecture)
    {
        $methodName = 'getName'.ucfirst($lang);
        $methodDescription = 'getDescription'.ucfirst($lang);

        $link = '/vbMifare/lectures/show-'. $lecture->getId() .'.html'
?>
        <li>
            <a href="<?php echo $link; ?>"><?php echo $lecture->$methodName(); ?></a>
            <p class="lectureDescription"><?php echo $lecture->$methodDescription(); ?></p>
        </li>
<?php
    }

    echo '</ul>';
?>
