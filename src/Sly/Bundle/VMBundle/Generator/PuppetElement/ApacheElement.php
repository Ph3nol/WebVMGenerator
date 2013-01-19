<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Apache Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ApacheElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'apache';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getApache();
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/apache', 'https://github.com/example42/puppet-apache.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $lines = <<< EOF
class { 'apache':
    require          => Exec['apt-update'],
    source_dir       => '/vagrant/files/apache',
    source_dir_purge => false,
}

apache::module { 'rewrite':
    ensure => present,
}
EOF;

        return $lines;
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess()
    {
        $apacheFilesPath = sprintf(
            '%s/%s/files/apache',
            $this->getGenerator()->getKernelRootDir(),
            $this->getVM()->getCachePath()
        );

        $this->getGenerator()->getFilesystem()->mkdir(array(
            $apacheFilesPath,
            $apacheFilesPath.'/sites-enabled',
        ), 0777);

        $vhosts = array(
            $this->getVM()->getHostname() => $this->getVM()->getApacheRootDir(),
        );

        $vhostIndex = 0;

        foreach ($vhosts as $vhostHostname => $vhostRootDir) {
            $vhostIndex++;

            $vhostFilename = sprintf('%d-%s.conf', $vhostIndex * 10, $vhostHostname);
            $vhostFilepath = sprintf('%s/sites-enabled/%s', $apacheFilesPath, $vhostFilename);

            $vhostContent = <<< EOF
<VirtualHost *:80>
    ServerName $vhostHostname
    ServerAdmin chuck-norris@$vhostHostname
    DocumentRoot /project$vhostRootDir

    <Directory "/project$vhostRootDir">
        AllowOverride all
        Order allow,deny
        Allow from all
    </Directory>

    ErrorLog  /var/log/apache2/$vhostHostname-error_log
    CustomLog /var/log/apache2/$vhostHostname-access_log common
</VirtualHost>

EOF;

            $this->getGenerator()->getFilesystem()->touch($vhostFilepath);
            file_put_contents($vhostFilepath, $vhostContent);
        }
    }
}
