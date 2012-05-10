<!-- Here we use our form because we need a div between two inputs -->

<form action="" method="post">
    <p><label for="vbmifareUsername">Username : </label><input type="text" name="vbmifareUsername" class="vbmifareUsername" autocomplete="off"/>

    <!-- Need for autocomplete system -->
    <span id="results"></span>
    </p>
</form>

<script type="text/javascript" src="/vbMifare/web/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/vbMifare/web/js/autocompleteUsername.js"></script>

<!-- Give focus on input field -->
<script type="text/javascript">
$(document).ready(function(){
    $(".vbmifareUsername").focus();
});
</script>
