#!/bin/bash

set -e

WORK_DIR="$(dirname $(readlink -f "${0}"))"

GIT_URL="git@github.com:Gerschtli/time-manager.git"
GIT_BRANCH="master"

GIT_DIR="${WORK_DIR}/project"
SHARED_DIR="${WORK_DIR}/shared"
WORKSPACE_DIR="${WORK_DIR}/workspace"

INSTALL_DIR="/var/www/time-manager"

BUILD=$(date '+%s')
MAX_BACKUPS=10

_log() {
    if [ "${2}" = "ERROR" ]; then
        echo -en "\033[31m"
    else
        echo -en "\033[32m"
    fi
    echo ${1}
    echo -en "\033[0m"

    if [ "${2}" = "ERROR" ]; then
        _log "aborting ..."
        exit 1
    fi
}
export -f _log

_export() {
    if [ ! -d "${GIT_DIR}" ]; then
        _log "cloning the repository ..."
        git clone "${GIT_URL}" "${GIT_DIR}"
    fi

    pushd "${GIT_DIR}" 1> /dev/null
    _log "get latest code base [git pull origin ${GIT_BRANCH}]..."
    git fetch --prune
    git checkout -f ${GIT_BRANCH}
    git pull origin ${GIT_BRANCH}
    popd 1> /dev/null
}

_composer() {
    pushd "${GIT_DIR}" 1> /dev/null
    if [ ! -f bin/composer.phar ]; then
        _log "Downloading composer ...."
        mkdir -p bin
        curl -sS "https://getcomposer.org/installer" | php -- --install-dir=bin
    else
        _log "Composer update...."
        php bin/composer.phar self-update
    fi

    php bin/composer.phar install --prefer-source --no-plugins --no-scripts --no-dev
    popd 1> /dev/null
}

_gulp() {
    pushd "${GIT_DIR}" 1> /dev/null
    _log "install npm dependencies ..."
    npm install

    _log "build gulp ..."
    ./node_modules/gulp/bin/gulp.js build --production
    popd 1> /dev/null
}

_build() {
    if [ -d "${WORKSPACE_DIR}" ]; then
        _log "remove old workspace ... "
        rm -r "${WORKSPACE_DIR}"
    fi
    _log "creating workspace ... "
    mkdir -p "${WORKSPACE_DIR}"

    _log "install code ..."
    cp -r "${GIT_DIR}/"* "${WORKSPACE_DIR}"
}

_updateConfig() {
    if [[ -f "${SHARED_DIR}/parameter.ini" ]]; then
        _log "update parameter.ini ..."
        cp "${SHARED_DIR}/parameter.ini" "${WORKSPACE_DIR}/app"
    else
        _log "parameter.ini does not exist!" "ERROR"
    fi

    if [[ -f "${SHARED_DIR}/phinx.yml" ]]; then
        _log "update phinx.yml ..."
        cp "${SHARED_DIR}/phinx.yml" "${WORKSPACE_DIR}"
    else
        _log "phinx.yml does not exist!" "ERROR"
    fi
}

_migrateDatabase() {
    pushd "${WORKSPACE_DIR}" 1> /dev/null
    _log "migrating database ..."
    php bin/phinx migrate
    popd 1> /dev/null
}

_cleanSource() {
    _log 'cleaning workspace ...'
    rm -rf "${WORKSPACE_DIR}/.git"
    rm "${WORKSPACE_DIR}/"*
    rm -r "${WORKSPACE_DIR}/bin" \
        "${WORKSPACE_DIR}/db" \
        "${WORKSPACE_DIR}/manifests" \
        "${WORKSPACE_DIR}/node_modules" \
        "${WORKSPACE_DIR}/scripts" \
        "${WORKSPACE_DIR}/tests"
}

_move() {
    _log 'moving the source to ...'
    if [ ! -d "${INSTALL_DIR}" ]; then
        _log 'create install dir ...'
        mkdir -p "${INSTALL_DIR}"
    fi
    mv "${WORKSPACE_DIR}" "${INSTALL_DIR}/BUILD-${BUILD}"
}

_link() {
    _log 'create a symbolic link ...'
    ln -snf "${INSTALL_DIR}/BUILD-${BUILD}" "${INSTALL_DIR}/current"
}

_cleanup() {
    _log 'cleanup ...'
    _log 'packing previous build(s) ...'
    BUILDS=( $(find "${INSTALL_DIR}" -maxdepth 1 -type d -name "BUILD-*" | sort -r ) )

    for B in "${!BUILDS[@]}"; do
        if [ "${BUILDS[${B}]}" != "BUILD-${BUILD}" ]; then
            tar -jcpf "${BUILDS[${B}]}.tar.bz2" "${BUILDS[${B}]}"
            rm -r "${BUILDS[${B}]}"
        fi
    done

    _log 'deleting old backup files ...'
    BUILDS=( $(find "${INSTALL_DIR}" -maxdepth 1 -type f -name "BUILD-*.tar.bz2" | sort -r ) )
    for B in "${!BUILDS[@]}"; do
        if [ ${B} -ge ${MAX_BACKUPS} ]; then
            rm "${BUILDS[${B}]}"
        fi
    done
}

_export
_composer
_gulp
_build
_updateConfig
_migrateDatabase
_cleanSource
_move
_link
_cleanup

exit 0
