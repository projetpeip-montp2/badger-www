<p><?php echo $TEXT['Package_ListOfSubscribed']; ?></p>

<?php
    if(count($packages) == 0)
        echo '<p>' . $TEXT['Package_NoSubscribtion'] . '</p>';

    else
    {
        echo '<ul>';

        foreach($packages as $package)
        {
            $link = '/lectures/show-'. $package->getId() .'.html'
?>
            <li>
                <a href="<?php echo $link; ?>"><?php echo $package->getName($lang); ?></a>
                <p class="lectureDescription"><?php echo $package->getDescription($lang); ?></p>
            </li>
<?php
        }

        echo '</ul>';
    }
?>
