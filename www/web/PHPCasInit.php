<?php
    $CasServer = 'cas.univ-montp2.fr'; 
    $CasPort = 443; 
    $CasURI = '/cas';

    phpCAS::client(CAS_VERSION_2_0, $CasServer, $CasPort, $CasURI);
    phpCAS::setNoCasServerValidation();
    phpCAS::forceAuthentication();

    $username =  phpCAS::getUser();

    $pos = strpos($username, '@');
    $logon = substr($username, 0, $pos);

    if( !isset($_SESSION['logDone']) )
    (
        // Emulate Polytech' Montpellier Intranet
        $_SESSION['logon'] = strtolower($logon);
    )
?>
