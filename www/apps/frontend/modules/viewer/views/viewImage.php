<div class="viewer">
    <h1><?php echo $packageName . ' - ' . $archiveName; ?></h1>
<?php
    $anchor = '#anchor';

    $form = new Form('redirectToImage.html', 'post');

    $form->add('hidden', 'idPackage')
         ->value($idPackage);
    $form->add('hidden', 'idArchive')
         ->value($idArchive);
    $form->add('label', 'Interval')
         ->label('Page ' . $imageNumber . '/' . $count);
    $form->add('text', 'imageNumber')
         ->value($imageNumber);

    $form->add('submit', $TEXT['Viewer_GoTo']);

    echo $form->toString();

    echo '<hidden id="anchor"/>';

    // Generate links to navigate

    $baseLink = '/viewer/viewImage-';

    if($imageNumber > 1)
    {
        $first = $baseLink . $idPackage . '-' . $idArchive . '-1.html';
?>

    <a href="<?php echo $first . $anchor; ?>" title="<?php echo $TEXT['Viewer_First']; ?>"><<</a> |

<?php
       $previous = $baseLink . $idPackage . '-' . $idArchive . '-' . ($imageNumber - 1) .'.html';
?>

    <a href="<?php echo $previous . $anchor; ?>" title="<?php echo $TEXT['Viewer_Previous']; ?>">< </a> |

<?php
    }

    if($imageNumber < $count)
    {
        $next = $baseLink . $idPackage . '-' . $idArchive . '-' . ($imageNumber + 1) .'.html';
?>

    | <a href="<?php echo $next . $anchor; ?>" title="<?php echo $TEXT['Viewer_Next']; ?>">></a>

<?php
        $last = $baseLink . $idPackage . '-' . $idArchive . '-' . $count . '.html';
?>

    | <a href="<?php echo $last . $anchor; ?> title="<?php echo $TEXT['Viewer_Last']; ?>"">>></a>

<?php
    }

    // Generate image
    $filePath = '/uploads/admin/images/' . $idPackage . '_' . $idArchive . '_' . $imageNumber . '.jpg';
    $fileName = basename($filePath);
?>
    <br/>
    <img src="<?php echo $filePath; ?>" alt="<?php echo $fileName; ?>"/>
    <br/>

<?php
    if($imageNumber > 1)
    {
        $first = $baseLink . $idPackage . '-' . $idArchive . '-1.html';
?>

    <a href="<?php echo $first . $anchor; ?>" title="<?php echo $TEXT['Viewer_First']; ?>"><<</a> |

<?php
       $previous = $baseLink . $idPackage . '-' . $idArchive . '-' . ($imageNumber - 1) .'.html';
?>

    <a href="<?php echo $previous . $anchor; ?>" title="<?php echo $TEXT['Viewer_Previous']; ?>"><</a> |

<?php
    }

    if($imageNumber < $count)
    {
        $next = $baseLink . $idPackage . '-' . $idArchive . '-' . ($imageNumber + 1) .'.html';
?>

    | <a href="<?php echo $next . $anchor; ?>" title="<?php echo $TEXT['Viewer_Next']; ?>">></a>

<?php
        $last = $baseLink . $idPackage . '-' . $idArchive . '-' . $count . '.html';
?>

    | <a href="<?php echo $last . $anchor; ?>" title="<?php echo $TEXT['Viewer_Last']; ?>">>></a>

<?php
    }
?>

</div>
