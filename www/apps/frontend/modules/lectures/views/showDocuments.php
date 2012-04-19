<h1>Documents du package <?php echo $packageName; ?><h1>
<ul>
<?php
    foreach($documents as $document)
    {
        if($document->getDownloadable())
        {
            $documentPath = $document->getPath() . $document->getFilename();
?>
<li><a href="<?php echo $documentPath; ?>"><?php echo $document->getFilename(); ?></a></li>
<?php
        }
    }
?>
</ul>
