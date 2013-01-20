# Define: php::pear::config
#
# Configures pear
#
# Usage:
# pear::config { http_proxy: value => "myproxy:8080" }
#
define pear::config ($value) {

    include pear

    exec {
        "pear-config-set-${name}":
            command => "pear config-set ${name} ${value}",
            unless  => "pear config-get ${name} | grep ${value}",
            require => Package["php-pear"],
    }

}

