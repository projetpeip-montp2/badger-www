<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $user->getAttribute('lang'); ?>">
    <head>
        <title>
            <?php echo $TEXT['Page_Title']; ?>
        </title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="<?php echo $user->getAttribute('lang'); ?>" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        
        <link rel="stylesheet" href="/web/styles/style.css" type="text/css" />
    </head>
    
    <body>
        <div id="UserBar">
            <span id="Flags">
            <a href="/home/changeLang-fr-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html"><img src="/web/images/french_flag.jpg" alt="Français"/></a>
            <a href="/home/changeLang-en-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html"><img src="/web/images/english_flag.jpg" alt="Anglais"/></a>
            </span>

            <?php echo $user->getAttribute('logon'); ?> | Département
        </div>

        <div id="Header">
            <a href="/home/index.html"><img src="/web/images/logo_polytech.png" alt="Logo Polytech Montpellier"/></a>
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
