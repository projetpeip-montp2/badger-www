<div class="viewer">
    <h1><?php echo $packageName; ?></h1>
<?php
    $anchor = '#anchor';

    $form = new Form('redirectToImage.html', 'post');

    $form->add('hidden', 'idPackage')
         ->value($idPackage);
    $form->add('label', 'Interval')
         ->label('Page ' . $imageNumber . '/' . $count);
    $form->add('text', 'imageNumber')
         ->value($imageNumber);

    $form->add('submit', $TEXT['Viewer_GoTo']);

    echo $form->toString();

    echo '<hidden id="anchor"/>';

    // Generate links to navigate

    $baseLink = '/vbMifare/viewer/viewImage-';

    if($imageNumber > 1)
    {
        $first = $baseLink . $idPackage . '-1.html';
?>

    <a href="<?php echo $first . $anchor; ?>"><<</a>

<?php
       $previous = $baseLink . $idPackage . '-' . ($imageNumber - 1) .'.html';
?>

    <a href="<?php echo $previous . $anchor; ?>"><</a>

<?php
    }

    if($imageNumber < $count)
    {
        $next = $baseLink . $idPackage . '-' . ($imageNumber + 1) .'.html';
?>

    <a href="<?php echo $next . $anchor; ?>">></a>

<?php
        $last = $baseLink . $idPackage . '-' . $count . '.html';
?>

    <a href="<?php echo $last . $anchor; ?>">>></a>

<?php
    }

    // Generate image
    $filePath = '/vbMifare/uploads/admin/images/' . $idPackage . '_' . $imageNumber . '.jpg';
    $fileName = basename($filePath);
?>
    <br/>
    <img src="<?php echo $filePath; ?>" alt="<?php echo $filename; ?>"/>
</div>
