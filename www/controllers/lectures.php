<?php
	require_once("models/lectures.php");
	includeView("lectures");

    function displayLecture($lecture)
    {
        $lectureId = "Lecture" . $lecture->getId();
        echo "<h2 id=\"" . $lectureId . "\">" . $lecture->getName() . "</h2>\n";
        echo "<div class=\"LectureHide\" id=\"" . $lectureId . "Div\">";
        echo $lecture->getDescription();
        echo "</div>\n";
    }
?>
