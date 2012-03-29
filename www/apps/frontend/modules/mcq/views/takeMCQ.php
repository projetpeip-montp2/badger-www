<?php
    $methodLabel = 'getLabel'.ucfirst($lang);

    $form = new Form('', 'post');

    // Vars to browse the answers array
    $i = 0;
    $size = count($answers) - 1;
    foreach($questions as $question)
    {
        echo $question->$methodLabel()."\n".'<br/><ul>';
        while($i < $size && $answers[$i]->getIdQuestion() == $question->getId())
        {
            $i++;
            echo '<li>'.$answers[$i]->$methodLabel()."\n".'</li><br/>';
        }
        echo '</ul><br/>';
    }
?>

