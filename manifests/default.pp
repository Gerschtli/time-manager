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

$mysqlServer   = "localhost"
$mysqlDatabase = "${appName}"
$mysqlUser     = "root"
$mysqlPassword = "root"

include apt
include system
include basePackage
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

class basePackage {
  package { "git":
    ensure  => 'latest',
    require => Exec["apt_update"]
  }
}

class httpserver {
  package { "apache2":
    ensure  => "latest",
    require => Exec["apt_update"]
  }

  service { "apache2":
    ensure  => "running",
    enable  => "true",
    require => Package["apache2"]
  }

  file { "${directory}":
    ensure => "directory",
    owner  => "${user}",
    group  => "${group}",
    mode   => "0775"
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
}

class dbserver {
  $sqlQueryDrop   = "DROP DATABASE `${mysqlDatabase}`;"
  $sqlQueryCreate = "
    CREATE DATABASE `${mysqlDatabase}`;
    GRANT ALL ON `${mysqlDatabase}`.* TO ${mysqlUser}@${mysqlServer} IDENTIFIED BY \"${mysqlPassword}\";
    GRANT ALL ON `${mysqlDatabase}`.* TO ${mysqlUser}@\"${appName}.local\" IDENTIFIED BY \"${mysqlPassword}\";
    FLUSH PRIVILEGES;
  "

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
    "mysql-server",
    "mysql-client",
    "phpmyadmin"
  ]:
    ensure  => "latest",
    require => Exec["apt_update"]
  }

  file { "/etc/apache2/conf-available/phpmyadmin.conf":
    alias   => "phpmyadminconf",
    ensure  => "link",
    target  => "/etc/phpmyadmin/apache.conf",
    require => Package['apache2']
  }

  exec { "enable-phpmyadmin":
    command => "a2enconf phpmyadmin.conf",
    unless  => "test -L /etc/apache2/conf-enabled/phpmyadmin.conf",
    require => File["phpmyadminconf"],
    notify  => Service["apache2"]
  }

  service { "mysql":
    enable  => true,
    ensure  => running,
    require => Package["mysql-server"]
  }

  exec { "external-access-to-mysql":
    unless  => "egrep -q '^#bind-address' /etc/mysql/my.cnf >> /dev/null 2>&1",
    command => "sed -i -e 's/^bind-address/#bind-address/' /etc/mysql/my.cnf",
    notify  => Service["mysql"],
    require => Package["mysql-server"]
  }

  exec { "set-mysql-password":
    unless  => "mysqladmin -u${mysqlUser} -p${mysqlPassword} status",
    command => "mysqladmin -u${mysqlUser} password ${mysqlPassword}",
    require => Service["mysql"]
  }

  exec { "drop-db":
    unless  => "mysql -u${mysqlUser} -p${mysqlPassword} ${mysqlDatabase} && exit 1 || exit 0",
    command => "mysql -uroot -p${mysqlPassword} -e '$sqlQueryDrop'",
    require => Exec["set-mysql-password"]
  }

  exec { "create-db":
    command => "mysql -uroot -p${mysqlPassword} -e '$sqlQueryCreate'",
    require => [
      Exec["set-mysql-password"],
      Exec["drop-db"]
    ]
  }

  exec { "create-tables":
    command => "mysql -uroot -p${mysqlPassword} < ${resourcePath}/tables.sql",
    require => [
      Exec["set-mysql-password"],
      Exec["drop-db"],
      Exec["create-db"]
    ],
    onlyif  => "test -e ${resourcePath}/tables.sql"
  }
}
