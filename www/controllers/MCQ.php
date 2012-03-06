<?php
    require_once("models/MCQ.php");
    includeView("MCQ");

    function displayQuestion($question)
    {
        $questionId = "Q" . $question->getId();
        echo "<label>" . $question->getLabel() . "</label>";
        echo "<br/>";

        $answers = $question->getAnswers();
        $counter = 1;

        foreach ($answers as $a)
        {
            $answerId = $questionId . "A" . $counter;
            echo "<input type=\"checkbox\" name=\"" . $answerId . "\" id=\"" . $answerId . "\"/>";
            echo "<label for=\"" . $answerId . "\">" . $a . "</label>   ";
            $counter++;
        }
    }
?>
