<ul>
    <li><a href="/admin/settings/changeAvailableAdmins.html">Configurations des admins</a></li>
    <li><a href="/admin/settings/changeSpecificLogins.html">Configurer les logins spéciaux</a></li>
    <li><a href="/admin/settings/replicate.html">Exécuter la réplication</a></li>
    <li><a href="/admin/settings/updateRegistrations.html">Mettre à jour les informations de présence</a></li>
    <li><a href="/admin/settings/uploadBadgingInformations.html">Uploader des informations de badgage</a></li>
    <li><a href="/admin/settings/computePresentMark.html">Calculer la note de présence</a></li>
</ul>



<script src="../../web/js/handlers.js"></script>
<script src="../../web/js/editTable.js"></script>
<script src="../../web/js/selectable.js"></script>

<br/>
<h1>Edition des variables de configuration:</h1>

<table id="editableTable">
    <tr>
        <th>Description</th>
        <th>Valeur</th>
    </tr>

<?php
    foreach($configNames as $name => $type)
    {
        $value = $configValues[$name];
        $size = strlen($value);

        echo '<tr>';
            echo '<td>' . utf8_encode($configDescriptions[$name]) . '</td>';

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
                $date = new Date();
                $date->setFromMySQLResult($value);

                echo '<td>';
                echo "<p class='editable' is-config-date='true' data-entry-name='Config' data-field-name='Value' data-subfield-name='Day' data-form-type='number' data-form-size='2' data-id='{$name}' >{$date->day(TRUE)}</p>";
                echo "<p class='separator'>-</p>";
                echo "<p class='editable' is-config-date='true' data-entry-name='Config' data-field-name='Value' data-subfield-name='Month' data-form-type='number' data-form-size='2' data-id='{$name}' >{$date->month(TRUE)}</p>";
                echo "<p class='separator'>-</p>";
                echo "<p class='editable' is-config-date='true' data-entry-name='Config' data-field-name='Value' data-subfield-name='Year' data-form-type='number' data-form-size='2' data-id='{$name}' >{$date->year(TRUE)}</p>";
                echo '</td>';
                break;

            default:
                throw new RuntimeException('Internal error during table generation');
                break;
        }

        echo '</tr>';
    }

?>
</table>

