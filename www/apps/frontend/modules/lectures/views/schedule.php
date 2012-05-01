<h1><?php ?></h1>
<p>Depuis cette page, vous pouvez voir le planning pour les conférences auxquelles vous êtes inscrit(e).</p>

<?php
    echo '<ul>';
    // Display all day with lecture
    foreach($lectures as $key => $lecture)
    {
        echo '<li>' . $key . '</li>';
        echo '<ul>';
        // Display lecture in this day
        foreach($lecture as $lect)
        {
            echo '<li>' . $lect->getName($lang) . '</li>';
            echo '<ul>';
            // Display informations for this lecture
            foreach($registrations as $reg)
            {
                if($lect->getId() == $reg->getIdLecture())
                    echo '<li>' . $TEXT['Planning_RegistrationStatus'] . ': ' . $TEXT['Planning_' . $reg->getStatus()] . '</li>';
            }
            echo '<li>' . $TEXT['Planning_Classroom'] . ': ' . '</li>';
            echo '<li>' . $TEXT['Lecture_StartTime'] . ': ' . $lect->getStartTime() . '</li>';
            echo '<li>' . $TEXT['Lecture_EndTime'] . ': ' . $lect->getEndTime() . '</li>';
            echo '</ul>';
        }
        echo '</ul>';
    }
    echo'</ul>';
?>
