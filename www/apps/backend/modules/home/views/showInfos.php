<script src="../../web/js/jquery-1.7.1.min.js"></script>
<script>
$(document).ready(function() {
   $('.hide').hide();

    $('.clicker').click(function(){
     $(this).children('.hide').toggle();
   });
});
</script>

<div class="clicker">
<h1>Packages sans conférence</h1>
    <div class="hide">
        <ul>
        <?php
            if(empty($noLecturePackages))
                echo '<p>Pas de package sans conférence.</p>';
            else
            {
                foreach($noLecturePackages as $package)
                    echo '<li>' . $package->getName('fr') . '</li>';
            }
        ?>
        </ul>
    </div>
</div>

<div class="clicker">
<h1>Packages avec trop peu d'inscriptions</h1>
    <div id="div2" class="hide">
        <ul>
        <?php
            if(empty($notEnoughRegPackages))
                echo '<p>Pas de package avec trop peu d\'inscriptions.</p>';
            else
            {
                foreach($notEnoughRegPackages as $package)
                    echo '<li>' . $package->getName('fr') . '</li>';
            }
        ?>
        </ul>
    </div>
</div>

<div class="clicker">
<h1>Questions sans réponse</h1>
    <div id="div3" class="hide">
        <ul>
        <?php
            if(empty($noAnswerQuestions))
                echo '<p>Pas de questions sans réponse.</p>';
            else
            {
                foreach($allPackages as $package)
                {
                    foreach($noAnswerQuestions as $question)
                    {
                        if($question->getIdPackage() == $package->getId())
                            echo '<li><strong>' . $package->getName('fr') . '</strong> - ' . $question->getLabel('fr') . '</li>';
                    }
                }
            }
        ?>
        </ul>
    </div>
</div>

<div class="clicker">
<h1>Etudiants avec des inscriptions manquantes</h1>
    <div id="div4" class="hide">
        <ul>
        <?php
            if(empty($incompleteStudents))
                echo '<p>Pas de questions sans réponse.</p>';
            else
            {
                foreach($incompleteStudents as $student)
                    echo '<li>' . $student->getName() . ' ' . $student->getSurname() . '</li>';
            }
        ?>
        </ul>
    </div>
</div>
