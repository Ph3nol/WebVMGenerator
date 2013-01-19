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
}
