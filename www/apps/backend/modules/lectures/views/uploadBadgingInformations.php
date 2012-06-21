<p>Uploader des informations de badgage<p>
<?php
    $form = new Form('', 'post');

    $form->add('file', 'CSVFile')
         ->isInParagraph(false);

    $form->add('submit', 'Envoyer')
         ->isInParagraph(false);

    echo $form->toString();
?>
