<?php
    echo $TEXT['MCQ_Introduction'];
   
    if($showMCQLink)
    {
        echo '<br/>';
        echo '<br/>';

        $form = new Form('/mcq/takeMCQ.html', 'post');

        $form->add('text', 'password')
             ->label($TEXT['MCQ_Password'] . ': ')
             ->isInParagraph(false);

        $form->add('submit', $TEXT['MCQ_StartMCQLink'])
             ->isInParagraph(false);

        echo $form->toString();
    }
?>
