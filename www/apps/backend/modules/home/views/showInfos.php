<script>
$(document).ready(function() {
   $('.informations').hide();
 
    $('.clicker').click(function(){
        $(this).children('.informations').toggle();

        var img = $(this).children('.informationsTitle').children('.imgPlusMinus');
        var state = true;                

        if(img.attr('src') == '../../web/images/plus.png')
            state = false;

            img.attr('src', '../../web/images/' + (state ? 'plus' : 'minus') + '.png');
    });
});
</script>

<div class="clicker">
<p class="informationsTitle" style="color: <?php echo (empty($noLecturePackages) ? 'black' : 'red')?>"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Packages sans conférence</p>
    <div class="informations">
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
<p class="informationsTitle" style="color: <?php echo (empty($notEnoughRegPackages) ? 'black' : 'red')?>"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Packages avec trop peu d'inscriptions</p>
    <div class="informations">
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
<p class="informationsTitle" style="color: <?php echo (empty($noAnswerQuestions) ? 'black' : 'red')?>"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Questions sans réponse</p>
    <div class="informations">
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
<p class="informationsTitle" style="color: <?php echo (empty($incompleteStudents) ? 'black' : 'red')?>"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Etudiants avec des inscriptions manquantes</p>
    <div class="informations">
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

<div class="clicker">
<p class="informationsTitle"><img class="imgPlusMinus" src='../../web/images/plus.png'>  Logs de réplication</p>
    <div class="informations">
    <?php
        if(empty($incompleteStudents))
            echo '<p>Pas de log de réplication.</p>';
        else
        {
        ?>
        <table border="1">
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Statut</th>
                    <th>Informations</th>
                </tr>
        <?php
            foreach($logs as $log)
            {
                echo '<tr><td>' . $log->getDate() .
                     '</td><td>' . $log->getTime() .
                     '</td><td>' . $log->getStatusCode() .
                     '</td><td>' . $log->getComment() . '</td></tr>';
            }
        }
        ?>
        </table>
    </div>
</div>
