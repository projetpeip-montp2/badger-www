<script src="../../web/js/jquery-1.7.1.min.js"></script>

<form id="form" method="post">
    Sélection du package: <select class="target" name="packageIdRequested">
<?php
    foreach($packages as $package)
    {
        echo '<option ' . ($package->getId() == $idPackage ? 'selected' : '') . ' value="' . $package->getId() . '">' . $package->getName('fr') . '</option>';
    }
?>
    </select>
</form>

<script type="text/javascript">
    // Submit form when select is changed
    $('.target').change(function() {

        $('#form').submit();
    });
</script>

<?php
    if(count($archives) == 0)
        echo $TEXT['Viewer_NoArchive'];

    foreach($archives as $archive)
    {
        $link = '/viewer/viewImage-' . $idPackage . '-' . $archive->getId() . '-1.html'; 
        ?>
        <a href="<?php echo $link; ?>"><?php echo $archive->getFilename(); ?></a><br/>
    <?php
    }
?>
