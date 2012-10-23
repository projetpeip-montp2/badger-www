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
/*
    var_dump($questions);
    echo '<br/><br/><br/>';
    var_dump($answers);
    echo '<br/><br/><br/>';
    var_dump($answersOfUser);
    die;
*/
    foreach($questions as $question)
    {
        echo $question->getLabel('fr');
        echo '<br/>';
        foreach($answers as $answer)
        {
            if($answer->getIdQuestion() == $question->getId())
            {
                foreach($answersOfUser as $answerOfUser)
                {
                    if($answerOfUser->getIdAnswer() == $answer->getId())
                        echo $answer->getLabel('fr');
                }
            }
        }
        echo '<br/>';
    }
}
