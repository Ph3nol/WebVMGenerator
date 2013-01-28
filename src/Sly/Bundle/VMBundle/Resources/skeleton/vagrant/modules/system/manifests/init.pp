class system {
	exec { "apt-update":
    	command => 'apt-get update',
    	path    => '/usr/bin/',
	}
}
