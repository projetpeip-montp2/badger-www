<h1><?php echo $packageName; ?><h1>
<ul>
<?php
    foreach($documents as $document)
    {
        $documentPath = '/uploads/admin/pdf/' . $document->getFilename();
?>
<li><a href="<?php echo $documentPath; ?>"><?php echo $document->getFilename(); ?></a></li>
<?php
    }
?>
</ul>
