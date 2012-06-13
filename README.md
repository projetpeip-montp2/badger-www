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

Pour pouvoir changer les fichiers de configurations des applications, il faut
penser à donner les droits d'écritures sur ceux-ci au serveur web.

Script
-------

- scripts/build_autoload.php

Ce script permet de créer automatiquement le fichier www/lib/autoload.php. 
La marche à suivre pour le lancer et la suivante:
 
    cd scripts/  
    php build_autoload.php  

- scripts/envoyerTout.sh

Attention: Il faut obligatoirement être dans le dossier scripts/ pour que
le script puissent fonctionner correctement!

Contacts
--------

- V. Berry  
- G. Guisez  
- J. Hennani  
- V. Hiairrassary  
- W. Tassoux  
