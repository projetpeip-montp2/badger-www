<p class="error">
    <?php echo $TEXT['MCQ_Warning']; ?>
</p>

<?php
    $form = new Form('', 'post');

    for($i=0; $i<count($questions); $i++)
    {
        $form->add('HTML', 'block')
             ->value('<div class="block-question">');
        $form->add('HTML', 'num-question')
             ->value('<div class="num-question">Question ' . ($i + 1) . '</div>');

        $form->add('HTML', 'title')
             ->value('<div class="title-question">' . $questions[$i]->getLabel($lang) . '</div>');

        foreach($answers as $answer)
        {
            if($questions[$i]->getId() == $answer->getIdQuestion())
            {
                // Don't remove or change $answer->getId()! It is used to 
                // retrieve the answers of the user
                $form->add('checkbox', $answer->getId())
                     ->label($answer->getLabel($lang));
            }
        }

        $form->add('HTML', 'endblock')
             ->value('</div>');
    }

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', $TEXT['MCQ_SubmitAnswers'])
         ->onClick('return window.confirm(\'' . $TEXT['MCQ_Confirmation'] . '\');');

    echo $form->toString();
?>
