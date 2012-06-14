<p>
    Moyenne globale: <?php echo $average; ?>
</p>
<p>

<p>
    Moyenne par département:
    <br/>
    <?php
        if(empty($departmentsAverage))
            echo 'Aucune promotion n\'a passé le QCM.';
        else
        {
    ?>
    <table border="1">
        <thead>
            <tr>
                <td>Départment</td>
                <td>Note</td>
            </tr>
        </thead>
        <tbody>
    <?php
            foreach($departmentsAverage as $name => $department)
                echo '<tr><td>'. $name . '</td><td>' . $department . '</td></tr>';
        }
    ?>
        </tbody>
    </table>
</p>
