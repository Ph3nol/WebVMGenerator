# Define: php::pear::module
#
# Installs the channel pear component
#
define pear::channel ($url) {
  exec {
    "pear-channel-${name}":
      creates => "/usr/share/php/.channels/${url}.reg",
      path => '/bin:/usr/bin:/usr/sbin',
      command => "pear channel-discover ${url}",
      require => Package['php-pear'],
  }
}
