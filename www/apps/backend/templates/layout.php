<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <title>
            Admin - Semaine du numérique
        </title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        
        <link rel="shortcut icon" href="/web/images/design/polytech.ico">
        <link rel="stylesheet" href="/web/styles/style.css" type="text/css" />
    </head>
    
    <body>
        <script src="../../web/js/jquery-1.7.1.min.js"></script>
        <div id="top">
	        <div id="top-content" align="right">
                <!-- Logout-->
                <a href="/home/logout.html">
                    <img src="/web/images/tools/door.png" alt="Logout"/>Déconnexion
                </a>
            </div>
        </div>

        <div id="wrapper">
            <div id="wrapper-body">
                <div id="header">
                    <a href="/admin/home/index.html"><div id="header-logo"><img src="/web/images/tools/logo.png" alt="" width="232" height="78" /></div></a>
                </div>

                <div id="content">

                    <div id="menu">
                        <?php require dirname(__FILE__).'/menu.php'; ?>
                    </div>

                    <?php 
                        if($user->hasFlash())
                        { 
                            echo '<div id="flash">';
                            echo '<p class="' . $user->getFlashType() . '">', $user->getFlash(), '</p>'; 
                            echo '</div><br/><br/>';
                        }
                    ?>

                    <div class="module-text">
                    	<div class="module-text-content">
                    		<div class="module-text-header">
                                <?php
                                    if(isset($viewTitle))
                                        echo $viewTitle;
                                    else
                                        echo 'Semaine du Numérique';
                                ?>
                            </div>
                            <div class="module-text-info">
                                <?php 
                                    echo $content;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div id="footer">
                    <p>Droits réservés &copy; 2011 - 2012 Polytech'Montpellier</p>
                </div>
            </div>
        </div>
    </body>
</html> 
