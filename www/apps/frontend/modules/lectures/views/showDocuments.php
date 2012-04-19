<h1>Documents du package <?php echo $packageName; ?><h1>
<ul>
<?php
    foreach($documents as $document)
    {
        $documentPath = '/vbMifare/uploads/admin/pdf/' . $document->getFilename();
?>
<li><a href="<?php echo $documentPath; ?>"><?php echo $document->getFilename(); ?></a></li>
<?php
    }
?>
</ul>
