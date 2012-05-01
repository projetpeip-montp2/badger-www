<div class="documentsFrame">
    <ul>
    <?php
        $documentsLink = '/vbMifare/lectures/showDocuments-' . $package->getId() . '.html';
        $imagesLink = '/vbMifare/viewer/viewImage-' . $package->getId() . '-' . '1.html';

        $noDisplay = true;
    ?>
    
    <?php
        // Display ShowDocument link
        if($showDocuments)
        {
    ?>
        <li><a href="<?php echo $documentsLink; ?>"><?php echo $TEXT['Package_DocumentsLink']; ?></a></li>
    <?php
        $noDisplay = false;
        }
    ?>

    <?php
        // Display viewImage link
        if($showImages)
        {
    ?>
        <li><a href="<?php echo $imagesLink; ?>"><?php echo $TEXT['Package_ImagesLink']; ?></a></li>
    <?php
        $noDisplay = false;
        }

        // No documents nor images available online
        if($noDisplay)
            echo '<li>' .$TEXT['Package_NoDisplay'] . '</li>';
    ?>
    </ul>
</div>

<h1><?php echo $package->getName($lang) . ' ' . $package->getRegistrationsCount() . '/' . $package->getCapacity()  . ' inscrits.' ?></h1>
<ul>
    <li><?php echo $TEXT['Package_Description'] . ': ' . $package->getDescription($lang); ?></li>
</ul>

<?php
    echo '<p>' . $TEXT['Package_ListOfLecture'] . '</p>';

    if(count($lectures) == 0)
    echo '<p>' . $TEXT['Package_NoLecture'] . '</p>';

    else
    {
        foreach($lectures as $lecture)
        {
?>
        <h1><?php echo $lecture->getName($lang); ?></h1>
        <ul>
            <li><?php echo $TEXT['Package_Description'] . ': ' . $lecture->getDescription($lang); ?></li>
            <li><?php echo $TEXT['Lecture_Lecturer'] . ': ' . $lecture->getLecturer(); ?></li>
            <li><?php echo $TEXT['Lecture_Date'] . ': ' . $lecture->getDate(); ?></li>
            <li><?php echo $TEXT['Lecture_StartTime'] . ': ' . $lecture->getStartTime(); ?></li>
            <li><?php echo $TEXT['Lecture_EndTime'] . ': ' . $lecture->getEndTime(); ?></li>
        </ul>
<?php
        }
    }
?>

<?php
    // Display the button only if registration is allowed
    if($registrationsAllowed)
    {
        $buttonName = $wantSubscribe ? $TEXT['Package_Subscribe'] : $TEXT['Package_Unsubscribe'];

        $form = new Form('', 'post');

        $form->add('hidden', 'isSubmitted')
             ->value('on');

        $form->add('submit', $buttonName);

        echo $form->toString();
    }
?>
