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
            Menu
            <ul>
                <li><a href="/vbMifare/admin/home/index.html">Accueil</a></li>
                <li><a href="/vbMifare/admin/lectures/index.html">Conférence</a></li>
                <li><a href="/vbMifare/admin/mcq/index.html">QCM</a></li>
                <li><a href="/vbMifare/admin/statistics/index.html">Statistiques</a></li>
                <li><a href="/vbMifare/admin/reset/index.html">Reset</a></li>
            </ul>
        </div>
        
        <div id="Body">
            <?php 
                if($user->hasFlash()) 
                    echo '<p id="Flash">', $user->getFlash(), '</p>'; 

                echo $content;
            ?>
        </div>
    
        <div id="Footer">
            Footer
        </div>
    </body>
</html> 
