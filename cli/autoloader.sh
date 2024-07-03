#!/bin/bash	
if ! [ -f "../../mu-plugins/bedrock-autoloader.php" ]; then	
    curl --silent -o ../../mu-plugins/bedrock-autoloader.php  https://raw.githubusercontent.com/roots/bedrock/cbdc0eae092f5692651e3605b75d96b77e36e678/web/app/mu-plugins/bedrock-autoloader.php
    echo 'Autoloader has been installed'
else
    echo 'Autoloader is already installed'
fi