ECRIRE UN TEXTE EXPLIQUANT QU'UN LIEN EST DISPO SI L'ETUDIANT PEUT PASSER LE QCM
<?php
    echo $TEXT['MCQ_Introduction'];
    if($showMCQLink)
    {
?>
    <br/>
    <a id='StartMCQ' href='/mcq/takeMCQ.html'><?php echo $TEXT['MCQ_StartMCQLink']; ?></a>
<?php
    }
?>
