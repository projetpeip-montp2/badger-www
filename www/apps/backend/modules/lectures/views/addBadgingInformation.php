<p>
Cette page permet de retrouver un élève et simuler sa présence à une 
conférence. Pour cela:
</p> 

<ul>
    <li>Rentrer l'identifiant de l'élève dans le champ ci-dessous.</li>

    <li>Une fois qu'il est reconnue par le système, choisir la
    conférence voulue dans la liste déroulante qui vient de s'afficher.</li>

    <li>Cliquer sur le bouton Envoyer.</li>
</ul>

<!-- Here we use our form because we need a div between two inputs -->
<form action="" method="post">
    <p><label for="vbmifareUsername">Identifiant : </label><input type="text" name="vbmifareUsername" class="vbmifareUsername" autocomplete="off"/>

    <!-- Need for autocomplete system -->
    <span id="results"></span>
    </p>
</form>


<script type="text/javascript" src="/web/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/web/js/autocompleteUsername.js"></script>


<!-- Give focus on input field -->
<script type="text/javascript">
$(document).ready(function(){
    $(".vbmifareUsername").focus();
});
</script>
