<?php
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="marks.csv"');

    echo $csv;
?>
