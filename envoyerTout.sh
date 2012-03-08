#!/bin/bash

cd www/

# 'mrm -r *' est optionnel car mirror gère le reste
lftp -c "open -u vbmifare sun5120.polytech.univ-montp2.fr;cd web;mirror -e -R  ./ ./"
cd ..
