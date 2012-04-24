<h1><?php ?></h1>
<p>Depuis cette page, vous pouvez voir le planning pour les conférences auxquelles vous êtes inscrit(e).</p>

<ul>
<?php
    foreach($lectures as $key => $lecture)
    {
        echo '<li>' . $key . '<ul>';
        foreach($lecture as $lect)
            echo '<li>' . $lect->getName($lang) . '</li>';
        echo'</ul></li>';
    }
?>
</ul>
