<ul>
    <li><a href="/admin/settings/changeAvailableAdmins.html">Configurations des admins</a></li>
    <li><a href="/admin/settings/changeSpecificLogins.html">Configurer les logins spéciaux</a></li>
    <li><a href="/admin/settings/replicate.html">Exécuter la réplication</a></li>
</ul>



<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>
<script src="../../web/js/selectable.js"></script>

<br/>
<br/>

<table id="editableTable">
    <tr>
        <th>Nom</th>
        <th>Valeur</th>
    </tr>

<?php
    foreach($configNames as $name => $type)
    {
        $value = $configValues[$name];
        $size = strlen($value);

        echo '<tr>';
            echo '<td>' . $name . '</td>';

        switch ($type) 
        {
            case 'textbox':
            case 'number':
		        echo "<td><p class='editable' data-id='{$name}' data-entry-name='Config' data-field-name='Value' data-form-type='{$type}' data-form-size='{$size}'>{$value}</p></td>";
                break;

            case 'binary':

            if ($value == '0')
                $select = "<option value='1'>Oui</option><option selected='selected' value='0'>Non</option>";

            else
                $select = "<option selected='selected' value='1'>Oui</option><option value='0'>Non</option>";

                echo "<td><select class='selectable' data-entry-name='Config' data-field-name='Value' data-id='{$name}'>{$select}</select></p></td>";
                break;

            case 'date':
                throw new RuntimeException('Non implemented');
                break;

            default:
                throw new RuntimeException('Internal error during table generation');
                break;
        }

        echo '</tr>';
    }

?>
</table>

