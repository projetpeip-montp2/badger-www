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
        <div id="top">
	        <div id="top-content" align="right">
                <a href="/vbMifare/home/changeLang-fr-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html">
                    <img src="/vbMifare/web/images/tools/french_flag.jpg" alt="Français" /> Français
                </a>
                <a href="/vbMifare/home/changeLang-en-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html">
                    <img src="/vbMifare/web/images/tools/english_flag.jpg" alt="Anglais"/> Anglais
                </a>
            </div>
        </div>

        <div id="wrapper">
            <div id="wrapper-body">
                <div id="header">
                    <a href="/vbMifare/home/index.html"><div id="header-logo"><img src="/vbMifare/web/images/tools/logo.png" alt="" width="232" height="78" /></div></a>
                    <div id="header-content">
                        <div id="header-info">
                        	<?php echo $user->getAttribute('logon'); ?>
                            <br/>
                            <?php 
                                $student = $user->getAttribute('vbmifareStudent');
                                echo $student->getDepartment() . ' ' . $student->getSchoolYear();
                            ?>
                        </div>
                    </div>
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
                            echo '</div>';
                        }
                    ?>

                    <?php
                        if($displayInfos)
                        {
                    ?>

                    <div id="registrations-infos">
                        Date de fin des inscriptions: <?php echo $limitDate; ?>
                        <br/>
                        Inscriptions: <?php echo $packagesChosen . '/' . $packagesToChoose; ?>
                    </div>

                    <?php
                        }
                    ?>

                    <div class="module-text">
                    	<div class="module-text-content">
                    		<div class="module-text-header">
                                <?php 
                                    echo $TEXT['Page_Title'];
                                ?>
                            </div>
                    <div class="module-text-info">
                            <?php 
                                echo $content;
                            ?>
                </div>

            </div>
    
                <div id="footer">
                    <p>Droits réservés &copy; 2011 - 2012 Polytech'Montpellier</p>
                </div>
            </div>
        </div>
    </body>
</html> 
