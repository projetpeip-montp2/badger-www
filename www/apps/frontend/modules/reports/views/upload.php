<form action="" method="post" enctype="multipart/form-data">
    <label for="idLecture"><?php echo $TEXT['Reports_SelectLecture']; ?></label>
    <br />
    <select name="idLecture" id="idLecture">
    <?php 
        foreach($packages as $pack)
        {
            echo '<optgroup label="' . $pack->getName($lang) . '">';
            
            foreach($lectures as $lec)
            {
                if($lec->getIdPackage() == $pack->getId())
                    echo '<option value="' . $lec->getId() . '">' . $lec->getName($lang) . '</option>';
            }

            echo '</optgroup>';
        }
    ?>
    </select>

    <input type="file" name="reportFile"/>
    <input type="submit" name="<?php echo $TEXT['Form_Send']?>" value="<?php echo $TEXT['Form_Send']?>"/>
</form>

