<?php
    echo '<ul>';

    foreach($packages as $package)
    {
        $link = '/lectures/show-'. $package->getId() .'.html'
?>
        <li>
            <a href="<?php echo $link; ?>"><?php echo $package->getName($lang); ?></a>

            <?php echo $package->getRegistrationsCount() . '/' . $package->getCapacity()  . ' inscrits.' ?>

            <p class="lectureDescription"><?php echo $package->getDescription($lang); ?></p>
        </li>
<?php
    }

    echo '</ul>';
?>
