<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $user->getAttribute('vbmifareLang'); ?>">
    <head>
        <title>
            <?php echo $TEXT['Page_Title']; ?>
        </title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="<?php echo $user->getAttribute('vbmifareLang'); ?>" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        
        <link rel="stylesheet" href="/vbMifare/web/styles/style.css" type="text/css" />
    </head>
    
    <body>
        <div id="UserBar">
            <span id="Flags">
            <a href="/vbMifare/home/changeLang-fr-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html"><img src="/vbMifare/web/images/french_flag.jpg" alt="Français"/></a>
            <a href="/vbMifare/home/changeLang-en-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html"><img src="/vbMifare/web/images/english_flag.jpg" alt="Anglais"/></a>
            </span>

            <?php echo $user->getAttribute('logon'); ?> |
            <?php 
                $student = $user->getAttribute('vbmifareStudent');
                echo $student->getDepartement(); 
                echo $student->getAnApogee(); 
            ?>
        </div>

        <div id="Header">
            <a href="/vbMifare/home/index.html"><img src="/vbMifare/web/images/logo_polytech.png" alt="Logo Polytech Montpellier"/></a>
        </div>
        
        <div id="Menu">
            Menu
        </div>
        
        <div id="Body">
            <?php echo $content; ?>
        </div>
    
        <div id="Footer">
            Footer
        </div>
    </body>
</html> 
