<div class="clicker">
<p class="informationsTitle"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Format CSV pour les disponibilitées</p>
    <div class="informations">
        <p>"Date","Heure de début","Heure de fin"</p>
    </div>
</div>

<script src="../../web/js/plusMinus.js"></script>

<?php
    $form = new Form('', 'post');

    $choices = array();

    foreach($classrooms as $classroom)
        $choices[$classroom->getId()] = $classroom->getName();

    $form->add('select', 'idClassroom')
         ->label('Sélection de la salle : ')
         ->choices($choices);

    $form->add('file', 'CSVFile')
         ->isInParagraph(false);

    $form->add('submit', 'Envoyer')
         ->isInParagraph(false);

    echo $form->toString();
?>
