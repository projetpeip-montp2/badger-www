<p><?php echo $TEXT['Package_ListOf']; ?></p>

<?php
    $lang;

    echo '<ul>';

    foreach($packages as $package)
    {
        $methodName = 'getName'.ucfirst($lang);
        $methodDescription = 'getDescription'.ucfirst($lang);

        $link = '/vbMifare/lectures/show-'. $package->getId() .'.html'
?>
        <li>
            <a href="<?php echo $link; ?>"><?php echo $package->$methodName(); ?></a>
            <p class="lectureDescription"><?php echo $package->getLecturer(); ?></p>
            <p class="lectureDescription"><?php echo $package->$methodDescription(); ?></p>
        </li>
<?php
    }

    echo '</ul>';
?>
