#!/bin/bash

BOLDTEXT=$(tput bold)
CYANBOLDTEXT=${BOLDTEXT}$(tput setaf 6)
BLUETEXT=$(tput setaf 4)
STDTEXT=$(tput sgr0)

echo $'\n'
echo "${BLUETEXT}######################################################"
echo "#${CYANBOLDTEXT}          PHPUnit Tests werden ausgefuehrt:         ${STDTEXT}${BLUETEXT}#"
echo "######################################################${STDTEXT}"
echo $'\n'
php -d "error_reporting=E_ALL|E_STRICT" bin/phpunit --color

echo $'\n'
echo "${BLUETEXT}######################################################"
echo "#${CYANBOLDTEXT}            CheckStyle wird ueberprueft:            ${STDTEXT}${BLUETEXT}#"
echo "######################################################${STDTEXT}"
echo $'\n'
php bin/phpcs --standard=tests/phpcs-ruleset.xml app lib tests

echo $'\n'
echo "${BLUETEXT}######################################################"
echo "#${CYANBOLDTEXT}            PMD Check wird durchgefuehrt:           ${STDTEXT}${BLUETEXT}#"
echo "######################################################${STDTEXT}"
echo $'\n'
php -d "date.timezone='Europe/Berlin'" bin/phpmd app,lib,tests text codesize,design,naming,unusedcode
echo $'\n'
