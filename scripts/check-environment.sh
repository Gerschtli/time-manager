#!/bin/bash

BASE_COMMANDS=( "nodejs" "npm" "curl" "git" "sass" "php" )

if [[ "${APPLICATION_ENV}" == "production" ]]; then
    CUSTOM_COMMANDS=( )
else
    CUSTOM_COMMANDS=( "virtualbox" "vagrant" )
fi

COMMANDS=("${BASE_COMMANDS[@]}" "${CUSTOM_COMMANDS[@]}")

_log() {
    if [[ "${2}" == "ERROR" ]]; then
        echo -en "\033[31m"
    else
        echo -en "\033[32m"
    fi
    echo ${1}
    echo -en "\033[0m"
}
export -f _log

for command in "${COMMANDS[@]}"; do
    which "${command}" >> /dev/null
    if [[ "${?}" != 0 ]]; then
        _log "${command} is required" "ERROR"
    else
        _log "${command} is installed"
    fi
done

exit 0
