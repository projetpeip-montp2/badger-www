<form id="redirect" action="/admin/questionsanswers/index.html" method="post">
    <input type="hidden" name="packageIdRequested" value="<?php echo $idPackage; ?>"/>
</form>

<script type="text/javascript">
$(document).ready(function() {
     $('#redirect').submit();
});
</script>
