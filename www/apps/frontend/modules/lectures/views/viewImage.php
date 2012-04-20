<div class="viewer">
    <h1><?php echo $packageName; ?></h1>
<?php
        var_dump($idPackage); var_dump($imageNumber); var_dump($count); die();
    $filePath = '/vbMifare/uploads/admin/images/' . $idPackage . '_' . $imageNumber . '.jpg';
    // Form creation
    $form = new Form('', 'get');

    $form->add('hidden', 'idPackage')
         ->value($idPackage);
    $form->add('label', 'NumberLabel')
         ->value('Page: [1-' . $count .']');
    $form->add('text', 'imageNumber')
         ->value($imageNumber);

    $form->add('submit', $TEXT['View_Goto']);

    echo $form->toString();

    // Generate links

    $baseLink = '/vbMifare/lectures/viewImage-';

    $first = $baseLink . $idPackage . '-1.html';
?>
    <a href="<?php echo $first; ?>"><<</a>

<?php
    // Generate previous link
    if($imageNumber > 1)
    {
        $previous = $baseLink . $idPackage . '-1.html';
?>
    <a href="<?php echo $previous; ?>"><</a>
<?php
    }
    // Display image
?>
        <img src="<?php echo $filePath; ?>" alt="<?php echo $filePath; ?>"/> 
 <?
    // Generate next link
    if($imageNumber < $count)
    {
        $next = $baseLink . $idPackage . '-' . ($imageNumber + 1) . '.html';
?>
    <a href="<?php echo $next; ?>"><</a>
<?php
    }
    $last = $baseLink . $idPackage . '-' . $count . '.html';
?>
    <a href="<?php echo $last; ?>">>></a>
</div>
