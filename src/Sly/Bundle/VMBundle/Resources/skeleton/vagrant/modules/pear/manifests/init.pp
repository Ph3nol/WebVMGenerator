# Class: pear
#
# Installs Pear for PHP module
#
# Usage:
#
class pear  {

    package { 
    	"pear":
        	name => $operatingsystem ? {
            	default => "php-pear",
            },
        ensure => present,
    }

    exec {
        "pear-upgrade":
            path => '/bin:/usr/bin:/usr/sbin',
            command => 'pear upgrade PEAR',
            require => Package['pear']
    }
}