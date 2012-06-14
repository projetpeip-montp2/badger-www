<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>

<?php
    $form = new Form('', 'post');

    $form->add('text', 'loginUM2')
         ->isInParagraph(true)
         ->label('Login um2: ');

    $form->add('text', 'loginPoly')
         ->isInParagraph(true)
         ->label('Login Polytech: ');

    $form->add('submit', 'Ajouter');

    echo $form->toString();
?>

<br/>
<br/>

<table id="editableTable">
    <tr>
        <th>Login UM2</th>
        <th>Login Polytech</th>
        <th>Action</th>
    </tr>

<?php
    foreach($specificLogins as $login)
    {
        $sizeLoginUM2 = strlen($login['UsernameUM2']);
        $sizeLogin = strlen($login['Username']);

		echo '<tr>';
		echo "<td><p class='labelable' data-form-size='{$sizeLoginUM2}'>{$login['UsernameUM2']}</p></td>";
		echo "<td><p class='labelable' data-form-size='{$sizeLogin}'>{$login['Username']}</p></td>";
		echo "<td><img class='deletable' data-entry-name='SpecificLogins' data-id='{$login['Id_login']}' src='../../web/images/delete.png' /></a></td>";
		echo '</tr>';
    }

?>
</table>

