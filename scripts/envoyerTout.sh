#!/bin/bash

cd ../www/

lftp -c "open -u vbmifare sun5120.polytech.univ-montp2.fr;cd web;mirror -e -R  ./ ./"
cd ..
