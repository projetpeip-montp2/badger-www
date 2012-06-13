<form action="" method="post">
    <label for="idLecture">Selectionner une conférence: </label>
    <br />
    <select name="idLecture" id="idLecture">
    <?php 
        foreach($packages as $pack)
        {
            echo '<optgroup label="' . $pack->getName('fr') . '">';
            
            foreach($lectures as $lec)
            {
                if($lec->getIdPackage() == $pack->getId())
                    echo '<option value="' . $lec->getId() . '">' . $lec->getName('fr') . '</option>';
            }

            echo '</optgroup>';
        }
    ?>
    </select>

    <input type="submit" name="Récupérer" value="Récupérer"/>
</form>
