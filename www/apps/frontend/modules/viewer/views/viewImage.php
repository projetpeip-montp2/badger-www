<div class="viewer">
    <h1><?php echo $packageName; ?></h1>
<?php
    // TODO: CORRIGER LE FORM EN POST
    $form = new Form('', 'get');

    $form->add('hidden', 'idPackage')
         ->value($idPackage);
    $form->add('label', 'Interval')
         ->label('Page ' . $imageNumber . '/' . $count);
    $form->add('text', 'imageNumber')
         ->value($imageNumber);

    $form->add('submit', $TEXT['Viewer_GoTo']);

    echo $form->toString();

    // Generate links to navigate

    $baseLink = '/vbMifare/viewer/viewImage-';

    if($imageNumber > 1)
    {
        $first = $baseLink . $idPackage . '-1.html';
?>

    <a href="<?php echo $first; ?>"><<</a>

<?php
       $previous = $baseLink . $idPackage . '-' . ($imageNumber - 1) .'.html';
?>

    <a href="<?php echo $previous; ?>"><</a>

<?php
    }

    if($imageNumber < $count)
    {
        $next = $baseLink . $idPackage . '-' . ($imageNumber + 1) .'.html';
?>

    <a href="<?php echo $next; ?>">></a>

<?php
        $last = $baseLink . $idPackage . '-' . $count . '.html';
?>

    <a href="<?php echo $last; ?>">>></a>

<?php
    }

    // Generate image
    $filePath = '/vbMifare/uploads/admin/images/' . $idPackage . '_' . $imageNumber . '.jpg';
    $fileName = basename($filePath);
?>

    <img src="<?php echo $filePath; ?>" alt="<?php echo $filename; ?>"/>
</div>
