<script src="../../web/js/jquery-1.7.1.min.js"></script>

<?php
    $documentsLink = '/lectures/showDocuments-' . $package->getId() . '.html';
    $downloadDocumentsLink = '/lectures/downloadDocuments-' . $package->getId() . '.html';
    $imagesLink = '/viewer/index.html';

    $noDisplay = true;
?>

<form id="linker" action="<?php echo $imagesLink; ?>" method="post">
    <input type="hidden" name="packageIdRequested" value="<?php echo $package->getId() ?>"/>
</form>

<script type="text/javascript">
$(document).ready(function() {
    $('#viewerLink').click(function(){
     $('#linker').submit();
   });
});
</script>

<div class="doc-dispo">    
    <?php
        // Display ShowDocument link
        if($showDocuments)
        {
    ?>
        <a href="<?php echo $documentsLink; ?>"><?php echo $TEXT['Package_DocumentsLink']; ?></a>
        <br/>
        <a href="<?php echo $downloadDocumentsLink; ?>"><?php echo $TEXT['Package_DownloadDocumentsLink']; ?></a>
    <?php
        $noDisplay = false;
        }
    ?>

    <?php
        // Display viewImage link
        if($showImages)
        {
            if($showDocuments)
                echo '<br/>';
    ?>
        <a style="cursor: pointer" id="viewerLink"><?php echo $TEXT['Package_ImagesLink']; ?></a>
    <?php
        $noDisplay = false;
        }

        // No documents nor images available online
        if($noDisplay)
            echo $TEXT['Package_NoDisplay'];
    ?>
</div>

<h3><?php echo $package->getName($lang) . ' ' . $package->getRegistrationsCount() . '/' . $package->getCapacity()  . ' inscrits.' ?></h3>
    <?php echo $TEXT['Package_Description'] . ': ' . $package->getDescription($lang); ?>

<?php
    echo '<p>' . $TEXT['Package_ListOfLecture'] . '</p>';

    if(count($lectures) == 0)
    echo '<p>' . $TEXT['Package_NoLecture'] . '</p>';

    else
    {
        foreach($lectures as $lecture)
        {
?>
        <div class="lecture">
            <div class="title"><?php echo $lecture->getName($lang); ?></div>
            <div class="description"><?php echo $TEXT['Package_Description'] . ': ' . $lecture->getDescription($lang); ?></div>
            <div class="lecturer"><?php echo $TEXT['Lecture_Lecturer'] . ': ' . $lecture->getLecturer(); ?></div>
            <div class="date"><?php echo $TEXT['Lecture_Date'] . ': ' . $lecture->getDate(); ?></div>
            <div class="hour"><?php echo $TEXT['Lecture_StartTime'] . ': ' . $lecture->getStartTime(); ?></div>
            <div class="hour"><?php echo $TEXT['Lecture_EndTime'] . ': ' . $lecture->getEndTime(); ?></div>
        </div>
<?php
        }
    }
?>

<?php
    // Display the button only if registration is allowed
    if($registrationsAllowed)
    {
        $buttonName = $wantSubscribe ? $TEXT['Package_Subscribe'] : $TEXT['Package_Unsubscribe'];

        $form = new Form('', 'post');

        $form->add('hidden', 'isSubmitted')
             ->value('on');

        $form->add('submit', $buttonName);

        echo $form->toString();
    }
?>
