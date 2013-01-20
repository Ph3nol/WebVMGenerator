class system
{
	exec {
    	"apt-update":
        	command => 'apt-get update',
        	path    => '/usr/bin/',
	}

    file
    {
        "project.basedir":
            ensure  => directory,
            path    => '/project',
    }
}