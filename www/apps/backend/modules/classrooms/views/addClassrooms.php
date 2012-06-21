<div class="clicker">
<p class="informationsTitle"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Format CSV pour les salles</p>
    <div class="informations">
        <p>"Nom","Taille"</p>
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
