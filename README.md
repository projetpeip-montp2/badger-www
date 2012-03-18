PROJET PEIP SEMAINE DU NUMERIQUE
================================

Description
-----------

Projet Polytech Montpellier PEIP  

Ce repository contient le site web pour la semaine du numérique.

Informations
------------

Afin de pouvoir tester le serveur en local, il faut avoir:

- apache (.htaccess d'activé dans le dossier www/ ainsi que l'URL rewriting)  
- php 5.3 minimum (avec mysqli d'activée)  
- mysql 5.0 minimun  

Scripts
-------

- scripts/build_autoload.php

Ce script permet de créer automatiquement le fichier www/lib/autoload.php. 
La marche à suivre pour le lancer et la suivante:
 
    cd scripts/  
    php build_autoload.php  

- scripts/envoyerTout.sh

Ce script permet quant à lui de mettre le contenu du dossier www/ sur
le serveur Polytech. Pour cela il faut avoir lftp d'installé. Les commandes
pour l'utiliser sont:

    cd scripts/  
    chmod u+x envoyerTout.sh  
    ./envoyerTout.sh  

Le script demandera alors le mot de passe du compte vbmifare pour éffectuer 
le transfert.  

Attention: Il faut obligatoirement être dans le dossier scripts/ pour que
les scripts puissent fonctionner correctement!

Contacts
--------

- V. Berry  
- G. Guisez  
- J. Hennani  
- V. Hiairrassary  
- W. Tassoux  
