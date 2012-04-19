<h1>Modification des salles</h1>
<p>Depuis cette page, il est possible de modifier les salles enregistrées dans la base de données.</p>
<?php
    $forms = array();
    foreach($classrooms as $classroom)
    {
        $form = new Form('', 'post');

        $form->add('text', 'Name')
             ->value($classroom->getName())
             ->size(strlen($classroom->getName()));
        $form->add('text', 'Size')
             ->value($classroom->getSize())
             ->size(3);

        $form->add('hidden', 'classroomId')
             ->value($classroom->getId());
        $form->add('submit', 'Modifier');
        $form->add('submit', 'Supprimer');

        $forms[] = $form;
    }
?>
<table class="FormTable">
    <tr>
        <th>Nom</th>
        <th>Contenance</th>
    </tr>
<?php
    foreach($forms as $form)
        echo $form->toTr();
?>
</table>

