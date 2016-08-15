Apt {
  update => {
    frequency => 'daily'
  }
}

Exec {
  path => [
    "/usr/local/sbin",
    "/usr/local/bin",
    "/usr/sbin",
    "/usr/bin",
    "/sbin",
    "/bin"
  ]
}

$appName       = "time-manager"

$user          = "vagrant"
$group         = "www-data"
$directory     = "/var/www/${appName}"
$resourcePath  = "${directory}/manifests/resources"

$logFolder     = "/var/log/php"
$logFile       = "${logFolder}/time-manager.log"

$mysqlServer   = "localhost"
$mysqlDatabase = "time_manager"
$mysqlUser     = "root"
$mysqlPassword = "root"

include apt
include system
include httpserver
include dbserver

class system {
  user { "${user}":
    gid    => "${group}",
    groups => ["${user}"]
  }

  host { "localhost":
    ip           => "127.0.0.1",
    host_aliases => [
      "${appName}.local"
    ]
  }
}

class httpserver {
  package { [
    "apache2",
    "php5-mysql",
    "php5-xdebug"
  ]:
    ensure  => "latest",
    require => Exec["apt_update"]
  }

  service { "apache2":
    ensure  => "running",
    enable  => "true",
    require => Package["apache2"]
  }

  file { [
      "${directory}",
      "${logFolder}"
    ]:
    ensure => "directory",
    owner  => "${user}",
    group  => "${group}",
    mode   => "0775"
  }

  file { "${logFile}":
    ensure  => "present",
    owner   => "${user}",
    group   => "${group}",
    mode    => "0775",
    replace => 'no'
  }

  file { "/etc/apache2/sites-available/${appName}.conf":
    alias   => "vhostconf",
    source  => "${resourcePath}/apache-vhost.conf",
    owner   => "root",
    group   => "root",
    mode    => "0644",
    require => [
      File["${directory}"],
      Package["apache2"]
    ],
    notify => Service['apache2']
  }

  exec { "enable-vhost":
    command => "a2ensite ${appName}",
    unless  => "test -L /etc/apache2/sites-enabled/${appName}.conf",
    require => File["vhostconf"],
    notify  => Service["apache2"]
  }

  exec { "enable-apache-rewrite":
    command => "a2enmod rewrite",
    unless  => "test -L /etc/apache2/mods-enabled/rewrite.load",
    notify  => Service["apache2"],
    require => Package["apache2"]
  }
}

class dbserver {
  $sqlQueryDrop   = "DROP DATABASE `${mysqlDatabase}`;"
  $sqlQueryCreate = "CREATE DATABASE `${mysqlDatabase}`;"

  file { "/root/.my.cnf":
    ensure  => present,
    owner   => "root",
    group   => "root",
    mode    => "0600",
    content => "[mysql]
      database = ${mysqlDatabase}
      host     = ${mysqlServer}
      password = ${mysqlPassword}
      user     = ${mysqlUser}
    "
  }

  file { "/home/vagrant/.my.cnf":
    ensure  => present,
    owner   => "vagrant",
    group   => "vagrant",
    mode    => "0600",
    content => "[mysql]
      database = ${mysqlDatabase}
      host     = ${mysqlServer}
      password = ${mysqlPassword}
      user     = ${mysqlUser}
    "
  }

  package { [
    "mysql-server"
  ]:
    ensure  => "latest",
    require => Exec["apt_update"]
  }

  service { "mysql":
    enable  => true,
    ensure  => running,
    require => Package["mysql-server"]
  }

  exec { "set-mysql-password":
    unless  => "mysqladmin -u${mysqlUser} -p${mysqlPassword} status",
    command => "mysqladmin -u${mysqlUser} password ${mysqlPassword}",
    require => Service["mysql"]
  }

  exec { "drop-db":
    unless  => "mysql -u${mysqlUser} -p${mysqlPassword} ${mysqlDatabase} && exit 1 || exit 0",
    command => "mysql -uroot -p${mysqlPassword} -e '${sqlQueryDrop}'",
    require => Exec["set-mysql-password"]
  }

  exec { "create-db":
    command => "mysql -uroot -p${mysqlPassword} -e '${sqlQueryCreate}'",
    require => [
      Exec["set-mysql-password"],
      Exec["drop-db"]
    ]
  }
}
