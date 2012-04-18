<p><?php echo $TEXT['Package_ListOf']; ?></p>

<?php
    echo '<ul>';

    foreach($packages as $package)
    {
        $link = '/vbMifare/lectures/show-'. $package->getId() .'.html'
?>
        <li>
            <a href="<?php echo $link; ?>"><?php echo $package->getName($lang); ?></a>
            <p class="lectureDescription"><?php echo $package->getLecturer(); ?></p>
            <p class="lectureDescription"><?php echo $package->getDescription($lang); ?></p>
        </li>
<?php
    }

    echo '</ul>';
?>
