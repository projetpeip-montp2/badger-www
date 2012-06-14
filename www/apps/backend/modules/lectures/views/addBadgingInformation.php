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

<!-- Here we use our form because we need divs between two inputs -->
<form action="" method="post">
    <input type="text" name="username" id="search" class="username" autocomplete="off"/>

    <span id="infos"></span>

    <div id="results"></div>
</form>


<script type="text/javascript" src="/web/js/autocompleteUsername.js"></script>


<!-- Give focus on input field -->
<script type="text/javascript">
$(document).ready(function(){
    $(".username").focus();
});
</script>
