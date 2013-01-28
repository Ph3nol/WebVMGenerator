define system::config ($source, $ensure = 'present') {
	file {
        "System_${name}":
        	name => "/home/vagrant/${name}",
            ensure => $ensure,
            source => $source,
    }
}
