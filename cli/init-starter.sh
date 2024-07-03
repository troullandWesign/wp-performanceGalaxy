#!/bin/bash
yarn install

if [ -d "../../plugins" ]; then
    composer install
fi