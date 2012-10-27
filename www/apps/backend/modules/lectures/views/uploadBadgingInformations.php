<p>Uploader des informations de badgage<p>

<div class="clicker">
<p class="informationsTitle"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Format CSV pour les informations de badgage</p>
    <div class="informations">
        <p>"Date", "Heure", "Mifare"</p>
    </div>
</div>

<script src="../../web/js/plusMinus.js"></script>

<?php
    $form = new Form('', 'post');

    $form->add('file', 'CSVFile')
         ->isInParagraph(false);

    $form->add('submit', 'Envoyer')
         ->isInParagraph(false);

    echo $form->toString();
?>
