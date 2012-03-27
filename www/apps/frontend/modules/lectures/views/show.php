<?php
    if(!$exists)
        echo $TEXT['Lecture_DoNotExists'];

    else
    {
        $lecture = $lectures[0];

        $methodName = 'getName'.ucfirst($lang);
        $methodDescription = 'getDescription'.ucfirst($lang);

        $wantSubscribe = !in_array($lecture->getId(), $registrations);

        $buttonName = $wantSubscribe ? $TEXT['Lecture_Subscribe'] : $TEXT['Lecture_Unsubscribe'];

        $yesOrNo = $wantSubscribe ? 1 : 0;

        $link = '/vbMifare/lectures/subscribe-' . $lecture->getId() . '-' . $yesOrNo . '.html';
?>
        <p>
            <h> <?php echo $lecture->$methodName(); ?> </h>
            <ul>
                <li><?php echo $TEXT['Lecture_Lecturer'] . ': ' . $lecture->getLecturer(); ?></li>
                <li><?php echo $TEXT['Lecture_Description'] . ': ' . $lecture->$methodDescription(); ?></li>
                <li><?php echo $TEXT['Lecture_Tags'] . ': ' . $lecture->getTags(); ?></li>
            </ul>

            <input type="button" name="subscribing" value="<?php echo $buttonName; ?>" onclick="self.location.href='<?php echo $link; ?>'">
        </p>
<?php
    }
?>
