<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <title>
            Semaine du numérique
        </title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        
        <link rel="stylesheet" href="/vbMifare/web/styles/style.css" type="text/css" />
    </head>
    
    <body>
        <div id="UserBar">
            <?php echo $user->getAttribute('logon'); ?>
        </div>

        <div id="Header">
            <a href="/vbMifare/admin/home/index.html"><img src="/vbMifare/web/images/logo_polytech.png" alt="Logo Polytech Montpellier"/></a>
        </div>

        <div id="Menu">
            <?php require dirname(__FILE__).'/menu.php'; ?>
        </div>
        
        <?php 
            if($user->hasFlash())
            { 
                echo '<div id="Flash">';
                echo '<p id="' . $user->getFlashType() . '">', $user->getFlash(), '</p>'; 
                echo '</div>';
            }
        ?>

        <div id="Body">
            <?php 
                echo $content;
            ?>
        </div>
    
        <div id="Footer">
            Footer
        </div>
    </body>
</html> 
