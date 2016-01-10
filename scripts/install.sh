#!/bin/bash

SCRIPT_DIR="$(dirname $(readlink -f "${0}"))"
UPDATE_CONFIG="$(dirname ${SCRIPT_DIR})/update-config.sh"
WORKSPACE_DIR="${SCRIPT_DIR}/workspace"

GIT_DIR="${SCRIPT_DIR}/project"
GIT_URL="git@github.com:Gerschtli/time-manager.git"
GIT_BRANCH="master"

if [[ "${APPLICATION_ENV}" == "development" ]]; then
    GIT_DIR="$(dirname ${SCRIPT_DIR})"
fi

_log(){
    if [[ "${2}" == "ERROR" ]]; then
        echo -en "\033[31m"
    else
        echo -en "\033[32m"
    fi
    echo ${1}
    echo -en "\033[0m"
}
export -f _log

_vagrant() {
    pushd "${GIT_DIR}" 1> /dev/null

    vagrant status | grep "running (virtualbox)" >> /dev/null
    if [[ "${?}" != 0 ]]; then
        _log "vagrant up ..."
        vagrant up --provision
    else
        _log "vagrant provision ..."
        vagrant provision
    fi

    popd 1> /dev/null
}

_export(){
    if [[ ! -d "${GIT_DIR}" ]]; then
        _log "cloning the repository ..."
        git clone "${GIT_URL}" "${GIT_DIR}"
    fi

    pushd "${GIT_DIR}" 1> /dev/null
    _log "get latest code base [git pull origin ${GIT_BRANCH}] ..."
    git fetch --prune
    git checkout -b "${GIT_BRANCH}"
    git checkout "${GIT_BRANCH}"
    git pull origin "${GIT_BRANCH}"
    popd 1> /dev/null
}

_composer() {
    pushd "${GIT_DIR}" 1> /dev/null

    COMPOSER="${GIT_DIR}/bin/composer.phar"
    if [[ ! -x "${COMPOSER}" ]]; then
        _log "download composer ..."
        mkdir -p bin
        curl -sS https://getcomposer.org/installer | php -- --install-dir=bin
    else
        _log "update composer ..."
        "${COMPOSER}" self-update
    fi

    _log "install composer dependencies ..."
    "${COMPOSER}" install

    popd 1> /dev/null
}

_gulp() {
    pushd "${GIT_DIR}" 1> /dev/null
    _log "install npm dependencies ..."
    npm install

    _log "build gulp ..."
    ./node_modules/gulp/bin/gulp.js build
    popd 1> /dev/null
}

_build() {
    if [[ -d "${WORKSPACE_DIR}" ]]; then
        _log "remove old workspace ... "
        rm -R "${WORKSPACE_DIR}"
    fi
    _log "creating workspace ... "
    mkdir -p "${WORKSPACE_DIR}"

    _log "install code ..."
    pushd "${GIT_DIR}" 1> /dev/null
    cp -R * "${WORKSPACE_DIR}"
    popd 1> /dev/null
}

_cleanSource() {
    pushd "${WORKSPACE_DIR}" 1> /dev/null
    _log "cleaning workspace ..."
    find . -name ".git" -exec rm -Rf {} \+
    find . -maxdepth 1 ! -name "." \
        -and ! -name ".." \
        -and ! -name "app" \
        -and ! -name "lib" \
        -and ! -name "public" \
        -and ! -name "vendor" \
        -exec rm -Rf {} \+
    rm -Rf "public/styles" "public/scripts" "public/assets/.gitkeep"
    popd 1> /dev/null
}

_updateConfig() {
    _log "update config ..."
    "${UPDATE_CONFIG}" "${WORKSPACE_DIR}/app/config.php"
}


_development() {
    _log "development build ..."
    _vagrant
    _composer
    _gulp
}

_production() {
    _log "production build ..."
    _export
    _composer
    _gulp
    _build
    _cleanSource
    _updateConfig
}
 

if [[ "${APPLICATION_ENV}" == "production" ]]; then
    _production
else
    _development
fi

exit 0
