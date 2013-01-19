<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * System Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class SystemElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'system';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $lines = array();

        $lines[] = "class { 'system': }'\n";

        foreach ($this->getVM()->getSystemPackages() as $package) {
            $lines[] = sprintf("system::package { '%s': }", $package);
        }

        $lines = implode("\n", $lines);

        $lines .= <<< EOF
\n
system::config { 'bash_aliases':
    name   => '.bash_aliases',
    source => '/vagrant/files/system/bash_aliases',
}

system::config { 'bashrc':
    name   => '.bashrc',
    source => '/vagrant/files/system/bashrc',
}
EOF;

        return $lines;
    }
}
