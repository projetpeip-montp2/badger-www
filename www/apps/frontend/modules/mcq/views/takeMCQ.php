<?php
    $methodLabel = 'getLabel'.ucfirst($lang);

    $form = new Form('', 'post');

    // TODO: Corriger la génération du QCM, problème dans l'ordre des questions
    // Vars to browse the answers array
    $i = 0;
    $size = count($answers) - 1;
    foreach($questions as $question)
    {
        $form->add('label', $question->$methodLabel());
        while($i < $size && $answers[$i]->getIdQuestion() == $question->getId())
        {
            $i++;
            $form->add('checkbox', $answers[$i]->$methodLabel());
        }
    }
    $form->add('submit', $TEXT['MCQ_SubmitAnswers']);
    echo $form->toString();
?>

