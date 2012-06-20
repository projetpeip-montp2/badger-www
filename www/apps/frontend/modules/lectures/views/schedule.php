<?php
    if($canViewPlanning)
    {
        if($output != '<ul></ul>')
        {
        ?>
        <form>
            <p><?php echo $TEXT['Lecture_GetMail']; ?>
            <input type="submit" name="<?php echo $TEXT['Form_Send']; ?>" onclick="return confirm('<?php echo $TEXT['Form_Send']; ?>');"/>
            </p>
        </form>
        <?php
            echo $output;
        }
        else
            echo $TEXT['Planning_NoRegistrations'];
    }

    else
    {
        echo $TEXT['Planning_Unavailable'];
    }
?>
