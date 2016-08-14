#!/bin/bash

SCRIPTPATH="$(dirname $(readlink -f "${0}"))"

if [ -r "${SCRIPTPATH}/install.conf" ]; then
    source "${SCRIPTPATH}/install.conf"
fi

GIT_URL="${GIT_URL:-git@github.com:Gerschtli/time-manager.git}"
GIT_DIR="${GIT_DIR:-${SCRIPTPATH}/project}"
GIT_BRANCH="${GIT_BRANCH:-master}"

WORK_DIR="${WORK_DIR:-${SCRIPTPATH}}"
WORKSPACE_DIR="${SCRIPTPATH}/workspace"
SHARED_DIR="${SCRIPTPATH}/shared"
INSTALL_DIR="${INSTALL_DIR:-/var/www/time-manager}"

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
}
export -f _log

_export() {
    if [ ! -d "${GIT_DIR}" ];then
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
    pushd "${WORK_DIR}"
    if [ ! -f "${WORK_DIR}/composer.phar" ];then
        _log "Downloading composer ...."
        curl -sS "https://getcomposer.org/installer" | php
    else
        _log "Composer update...."
        php composer.phar self-update
    fi
    popd 1> /dev/null

    pushd "${GIT_DIR}" 1> /dev/null
    php "${WORK_DIR}/composer.phar" install --prefer-source --no-dev
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
    if [ -d "${WORKSPACE_DIR}" ];then
        _log "remove old workspace ... "
        rm -R "${WORKSPACE_DIR}"
    fi
    _log "creating workspace ... "
    mkdir -p ${WORKSPACE_DIR}

    _log "install code ..."
    pushd "${GIT_DIR}" 1> /dev/null
    cp -R * "${WORKSPACE_DIR}"
    popd 1> /dev/null
}

_updateConfig() {
    if [[ -f "${SHARED_DIR}/parameter.ini" ]]; then
        _log "update config ..."
        cp "${SHARED_DIR}/parameter.ini" "${WORKSPACE_DIR}/app"
    else
        _log "parameter.ini does not exist!" "ERROR"
    fi
}

_cleanSource() {
    _log 'cleaning workspace ...'
    pushd "${WORKSPACE_DIR}" 1> /dev/null
    find . -name ".git" -exec rm -Rf {} \+
    find . -maxdepth 1 ! -name "." \
        -and ! -name ".." \
        -and ! -name "app" \
        -and ! -name "dist" \
        -and ! -name "lib" \
        -and ! -name "vendor" \
        -exec rm -Rf {} \+
    popd 1> /dev/null
}

_move() {
    _log 'moving the source to ...'
    mv "${WORKSPACE_DIR}" "${INSTALL_DIR}/BUILD-${BUILD}"
}

_link() {
    _log 'create a symbolic link ...'
    ln -snf "${INSTALL_DIR}/BUILD-${BUILD}" "${INSTALL_DIR}/current"
}

_reloadApache() {
    _log 'Reload Apache ...'
    service apache2 reload
}

_cleanup() {
    _log 'cleanup ...'
    pushd ${INSTALL_DIR} 1> /dev/null
    _log 'packing previous build(s) ...'
    BUILDS=( $(find . -maxdepth 1 -type d -name "BUILD-*" -printf "%f\n" | sort -r ) )
    for B in "${!BUILDS[@]}"; do
        if [ "${BUILDS[${B}]}" != "BUILD-${BUILD}" ]; then
            tar -jcpf ${BUILDS[${B}]}.tar.bz2 ${BUILDS[${B}]} --exclude='logs/*'
            rm -Rf ${INSTALL_DIR}/${BUILDS[${B}]}
        fi
    done
    _log 'deleting old backup files ...'
    BUILDS=( $(find . -maxdepth 1 -type f -name "BUILD-*.tar.bz2" | sort -r ) )
    for B in "${!BUILDS[@]}"; do
        if [ ${B} -ge ${MAX_BACKUPS} ]; then
            rm -Rf ${INSTALL_DIR}/${BUILDS[${B}]}
        fi
    done
    popd 1> /dev/null
}

_main() {
    _export
    _composer
    _gulp
    _build
    _updateConfig
    _cleanSource
    _move
    _link
    _reloadApache
    _cleanup
}

_main

exit 0
