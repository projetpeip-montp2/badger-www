<?php
    $methodName = 'getName'.ucfirst($lang);
    $methodDescription = 'getDescription'.ucfirst($lang);
?>

    <h1><?php echo $lecture->$methodName(); ?></h1>
    <ul>
        <li><?php echo $TEXT['Lecture_Lecturer'] . ': ' . $lecture->getLecturer(); ?></li>
        <li><?php echo $TEXT['Lecture_Description'] . ': ' . $lecture->$methodDescription(); ?></li>
        <li><?php echo $TEXT['Lecture_Tags'] . ': ' . $lecture->getTags(); ?></li>
    </ul>

<?php
    $buttonName = $wantSubscribe ? $TEXT['Lecture_Subscribe'] : $TEXT['Lecture_Unsubscribe'];

    $form = new Form('', 'post');

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', $buttonName);

    echo $form->toString();
?>
