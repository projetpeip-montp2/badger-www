<?php
    $form = new Form('', 'post');

    $form->add('text', 'newAdmin')
         ->isInParagraph(false)
         ->label('Nouvel administrateur: ');

    $form->add('submit', 'Ajouter');

    echo $form->toString();
?>

<br/>
<table id="editableTable">
    <tr>
        <th>Login</th>
        <th>Action</th>
    </tr>

<?php
    foreach($currentAdminList as $current)
    {
        echo '<tr>';
            echo '<td>' . $current . '</td>';

            echo '<td>
                      <form action="" method="post">
                          <input type="hidden" name="deletedAdmin" value="' . $current . '" />
                          <input type="image" onclick="$(this).submit();" src="../../web/images/delete.png"/>
                      </form>
                  </td>';
        echo '</tr>';
    }

?>
</table>


