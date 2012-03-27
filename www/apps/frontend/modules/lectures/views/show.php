<?php
    if(!$exists)
        echo $TEXT['Lecture_DoNotExists'];

    else
    {
        $lecture = $lectures[0];

        $methodName = 'getName'.ucfirst($lang);
        $methodDescription = 'getDescription'.ucfirst($lang);
?>
        <p>
            <h> <?php echo $lecture->$methodName(); ?> </h>
            <ul>
                <li><?php echo $TEXT['Lecture_Lecturer'] . ': ' . $lecture->getLecturer(); ?></li>
                <li><?php echo $TEXT['Lecture_Description'] . ': ' . $lecture->$methodDescription(); ?></li>
                <li><?php echo $TEXT['Lecture_Tags'] . ': ' . $lecture->getTags(); ?></li>
            </ul>
        </p>
<?php
    }
?>
