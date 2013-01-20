# Define: php::pear::module
#
# Installs the defined php pear component
#
# Variables:
# $use_package (default="yes") - Tries to install pear module with the relevant package
#                                If set to "no" it installs the module via pear command
# $preferred_state (default="stable") - Define which preferred state to use when installing Pear modules via pear
#                                command line (when use_package=no)
# Usage:
# pear::module { packagename: }
# Example:
# pear::module { Crypt-CHAP: }
#
define pear::module (
    $channel_name = "pear",
    $package_name,
    $dir_source) {

    exec {
        "pear-${name}":
            path => "/bin:/usr/bin:/usr/sbin",
            command => "pear install -a ${channel_name}/${package_name}",
            unless => $operatingsystem ? {
            	default => "/usr/bin/test -d /usr/share/php/${dir_source}",
            },
            require => Exec['pear-upgrade'],
    }
}
