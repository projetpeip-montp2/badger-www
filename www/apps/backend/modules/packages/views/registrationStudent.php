<?php if(!isset($display))
{
?><form action="" method="post">
    <input type="text" name="username" id="search" class="username" autocomplete="off"/>

    <span id="infos"></span>

    <div id="results"></div>
</form>

<br/><div style="border-top: 1px solid black">
<?php
}
else
{
?>
    Inscriptions de <strong id="user"><?php echo $username; ?></strong>
    <br/>
    <form>
        <ul>
<?
    foreach($packages as $package)
    {
        $registered = false;
        foreach($registrations as $reg)
        {
            if($package->getId() == $reg->getIdPackage())
                $registered = true;
        }
        echo '<li><label for="' . $package->getId() . '">' . $package->getName('fr') . '</label>
               <input type="checkbox" name="' . $package->getId() . '" id="' . $package->getId() . '"';

        // Tick the checkbox if the student registered to the package
        if($registered)
            echo ' checked /></li>';
        else
            echo ' /></li>';
    }
?>
        </ul>
    </form>
<?php
}
?>

<script type="text/javascript" src="/web/js/autocompleteUsername2.js"></script>

<script type="text/javascript">
function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    alert(out);
}


function updateCheckboxes(checkbox)
{
    var idTmp = -1;
    if(checkbox)
        idTmp = checkbox.attr('id');

    $.ajax(
    {
        type: "POST",
        url: "/admin/ajax/checkLecturesConflict.html",
        data:
        {
            username:$('#user').html(),
            idPackage:idTmp
        }
    }).error(function() {
        alert('Erreur: Connexion au site échouée, les modifications faites n\'ont pas été prises en compte. La page va se recharger.');
    	location.reload();
    }).done(function(msg) {
        msg = jQuery.parseJSON(msg);
        dump(msg);

//        $('#' + msg.idPackage).removeAttr('checked');
    });
}

$(document).ready(
    function()
    {
        // Give focus on input field
        $(".username").focus();
        updateCheckboxes(null);

        $('input:checkbox').bind('click', function() {
            updateCheckboxes($(this));
        });
    });
</script>
