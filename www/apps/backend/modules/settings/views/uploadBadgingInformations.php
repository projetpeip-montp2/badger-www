<p>Uploader des lectures pour ce package<p>
<?php
    $form = new Form('', 'post');

    $form->add('file', 'CSVFile')
         ->isInParagraph(false);

    $form->add('submit', 'Envoyer')
         ->isInParagraph(false);

    echo $form->toString();
?>
