    <h1><?php echo $package->getName($lang); ?></h1>
    <ul>
        <li><?php echo $TEXT['Package_Lecturer'] . ': ' . $package->getLecturer(); ?></li>
        <li><?php echo $TEXT['Package_Description'] . ': ' . $package->getDescription($lang); ?></li>
    </ul>


    <br/>
    <br/>

<?php
    echo $TEXT['Package_ListOfLecture'];

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
?>

<?php
    $buttonName = $wantSubscribe ? $TEXT['Package_Subscribe'] : $TEXT['Package_Unsubscribe'];

    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', $buttonName);

    echo $form->toString();
?>
