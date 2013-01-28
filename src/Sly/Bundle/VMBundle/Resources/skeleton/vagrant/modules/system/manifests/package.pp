define system::package (
	$ensure = 'present') {
	include system

	package {
		"Tools_${name}":
            ensure  => $ensure,
            name	=> $name,
            require	=> Exec['apt-update'],
    }
}
