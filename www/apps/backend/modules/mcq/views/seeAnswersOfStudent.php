<p>
Cette page permet de voir les questions et réponses d'un étudiant.
</p> 

<?php
if($selectedStudent == null)
{
?>
<!-- Here we use our form because we need divs between two inputs -->
<form action="" method="post">
    <input type="text" name="username" id="search" class="username" autocomplete="off"/>

    <span id="infos"></span>

    <div id="results"></div>
</form>


<script type="text/javascript" src="/web/js/autocompleteUsername2.js"></script>


<!-- Give focus on input field -->
<script type="text/javascript">
$(document).ready(function(){
    $(".username").focus();
});
</script>
<?php
}
else
{
    $mail = '<ul>';
    foreach($questions as $question)
    {
        $answered = false;

        $mail .= '<li>' . $question->getLabel('fr') . '</li>';
        $mail .= '<ul>';
        foreach($answers as $answer)
        {
            if($answer->getIdQuestion() == $question->getId())
            {
                foreach($answersOfUser as $answerOfUser)
                {
                    if($answerOfUser->getIdAnswer() == $answer->getId())
                    {
                        $answered = true;
                        $mail .= '<li>' . $answer->getLabel('fr') . '</li>';
                    }
                }
            }
        }

        if(!$answered)
            $mail .= '<li>Pas de réponse à cette question.</li>';
        $mail .= '<br/></ul>';
    }
    $mail .= '</ul>';

    echo $mail;
}
