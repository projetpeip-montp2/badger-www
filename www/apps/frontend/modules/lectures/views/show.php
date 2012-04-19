<div class="documentsFrame">
    <?php
        $documentsLink = '/vbMifare/lectures/showDocuments-' . $package->getId() . '.html';
    ?>
    <a href="<?php echo $documentsLink; ?>"><?php echo $TEXT['Package_DocumentsLink']; ?></a>
</div>

<h1><?php echo $package->getName($lang); ?></h1>
<ul>
    <li><?php echo $TEXT['Package_Lecturer'] . ': ' . $package->getLecturer(); ?></li>
    <li><?php echo $TEXT['Package_Description'] . ': ' . $package->getDescription($lang); ?></li>
</ul>

<br/>

<?php
    echo '<p>' . $TEXT['Package_ListOfLecture'] . '</p>';

    if(count($lectures) == 0)
    echo '<p>' . $TEXT['Package_NoPackage'] . '</p>';

    else
    {
        foreach($lectures as $lecture)
        {
?>
        <h1><?php echo $lecture->getName($lang); ?></h1>
        <ul>
            <li><?php echo $TEXT['Package_Description'] . ': ' . $lecture->getDescription($lang); ?></li>
            <li><?php echo $TEXT['Lecture_Date'] . ': ' . $lecture->getDate(); ?></li>
            <li><?php echo $TEXT['Lecture_StartTime'] . ': ' . $lecture->getStartTime(); ?></li>
            <li><?php echo $TEXT['Lecture_EndTime'] . ': ' . $lecture->getEndTime(); ?></li>
        </ul>
<?php
        }
    }
?>

<?php
    $buttonName = $wantSubscribe ? $TEXT['Package_Subscribe'] : $TEXT['Package_Unsubscribe'];

    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', $buttonName);

    echo $form->toString();
?>
