<p>Cette page permet de vider certaines tables.</p>

<?php
    $form = new Form('', 'post');

    $num = count($checkboxes);
    
    for ($i=0; $i<$num; $i++)
    {
        $form->add('checkbox', $checkboxes[$i])
             ->label($checkboxes[$i]);
    }

    $form->add('hidden', 'isSubmitted')
         ->value('on');

    $form->add('submit', 'Vider');

    echo $form->toString();
?>
