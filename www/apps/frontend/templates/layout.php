<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $user->getAttribute('vbmifareLang'); ?>">
    <head>
        <title>
            <?php echo $TEXT['Page_Title']; ?>
        </title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="<?php echo $user->getAttribute('vbmifareLang'); ?>" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />

        <link rel="shortcut icon" href="/web/images/design/polytech.ico">
        <link rel="stylesheet" href="/web/styles/style.css" type="text/css" />
    </head>
    
    <body>
        <div id="top">
	        <div id="top-content" align="right">
                <!-- Flag french-->
                <a href="/home/changeLang-fr-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html">
                    <img src="/web/images/tools/french_flag.jpg" alt="Français" /> <?php echo $TEXT['Page_French']; ?>
                </a>

                <!-- Flag english-->
                <a href="/home/changeLang-en-<?php echo $user->app()->httpRequest()->requestURI(); ?>.html">
                    <img src="/web/images/tools/english_flag.jpg" alt="Anglais"/> <?php echo $TEXT['Page_English']; ?>
                </a>

                <!-- Logout-->
                <a href="/home/logout.html"><?php echo '   | ', $TEXT['Page_Logout']; ?></a>
            </div>
        </div>

        <div id="wrapper">
            <div id="wrapper-body">
                <div id="header">
                    <a href="/home/index.html"><div id="header-logo"><img src="/web/images/tools/logo.png" alt="" width="232" height="78" /></div></a>
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
                        <?php echo $TEXT['Info_LimitDate'] .$limitDate; ?>
                        <br/>
                        <?php echo $TEXT['Info_RegistrationsCount'] . $packagesChosen . '/' . $packagesToChoose; ?>
                    </div>

                    <?php
                        }
                    ?>

                    <div class="module-text">
                    	<div class="module-text-content">
                    		<div class="module-text-header">
                                <?php
                                    if(isset($viewTitle))
                                        echo $viewTitle;
                                    else
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
                    <a href="/home/legalNotice.html">Mentions légales</a>
                    <p>Droits réservés &copy; 2011 - 2012 Polytech'Montpellier</p>
                </div>
            </div>
        </div>
    </body>
</html> 
