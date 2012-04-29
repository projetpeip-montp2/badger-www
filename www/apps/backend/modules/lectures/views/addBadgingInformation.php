<!-- Here we use our form because we need a div between two inputs -->

<form action="" method="post">
    <p><label for="vbmifareUsername">Username : </label><input type="text" name="vbmifareUsername" class="vbmifareUsername"/></p>
    <!-- Use for autocomplete system -->
    <div id="results"></div>


    <p><label for="vbmifareDate">Date : </label><input type="text" name="vbmifareDate" class="vbmifareDate" value="" size="20"/></p>

    <p><label for="vbmifareTime">Heure : </label><input type="text" name="vbmifareTime" class="vbmifareTime" value="" size="20"/></p>

    <input type="submit" name="Envoyer" value="Envoyer"/>
</form>

<script type="text/javascript" src="/vbMifare/web/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/vbMifare/web/js/autocompleteUsername.js"></script>
