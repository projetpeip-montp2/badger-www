<?php
    // TODO: Do better..
    function getClassroomName($classrooms, $availabilities, $idAvailability)
    {
        $result = 'Unknown classroom';

        foreach($availabilities as $avail)
        {
            if($avail->getId() == $idAvailability)
            {
                foreach($classrooms as $room)
                {
                    if($room->getId() == $avail->getIdClassroom())
                        $result = $room->getName();
                }
            }
        }

        return $result;
    }

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
            
            $idAvailability = $lect->getIdAvailability();
            $room = ($idAvailability == 0) ? $TEXT['Planning_NoClassroom'] : getClassroomName($classrooms, $availabilities, $idAvailability);

            echo '<li>' . $TEXT['Planning_Classroom'] . ': ' . $room . '</li>';
            echo '<li>' . $TEXT['Lecture_StartTime'] . ': ' . $lect->getStartTime() . '</li>';
            echo '<li>' . $TEXT['Lecture_EndTime'] . ': ' . $lect->getEndTime() . '</li>';
            echo '</ul>';
        }
        echo '</ul>';
    }
    echo'</ul>';
?>
