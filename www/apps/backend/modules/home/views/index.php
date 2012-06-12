<h1>Accueil</h1>

<h2>Packages sans conférence :</h2>
<div id="khqgse" class="hide">
<ul>
<?php
    foreach($noLecturePackages as $package)
        echo '<li>' . $package->getName('fr') . '</li>';
?>
</ul>
</div>

<h2>Packages avec trop peu d'inscriptions :</h2>
<div id="khbfee" class="hide">
<ul>
<?php
    foreach($notEnoughRegPackages as $package)
        echo '<li>' . $package->getName('fr') . '</li>';
?>
</ul>
</div>

<h2>Questions sans réponses :</h2>
<div id="jhef" class="hide">
<ul>
<?php
    // TODO: Afficher le nom du package
    foreach($noAnswerQuestions as $question)
        echo '<li>' . $question->getLabel('fr') . '</li>';
?>
</ul>
</div>

<h2>Etudiants avec des inscriptions manquantes :</h2>
<div id="kjef" class="hide">
<ul>
<?php
    foreach($incompleteStudents as $student)
        echo '<li>' . $student->getName() . ' ' . $student->getSurname() . '</li>';
?>
</ul>
</div>
