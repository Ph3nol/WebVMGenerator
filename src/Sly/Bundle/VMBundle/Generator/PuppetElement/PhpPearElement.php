<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * PHP PEAR Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PhpPearElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'phpPear';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) ($this->getVM()->getPhp() && $this->getVM()->getPhpPear());
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $lines = <<< EOF
class { 'pear': }

pear::channel { 'phpunit':
    url => 'pear.phpunit.de',
}

pear::channel { 'symfony2':
    url     => 'pear.symfony.com',
    require => Exec['pear-channel-phpunit'],
}

pear::channel { 'symfony1':
    url     => 'pear.symfony-project.com',
    require => Exec['pear-channel-symfony2'],
}

pear::channel { 'components':
    url     => 'components.ez.no',
    require => Exec['pear-channel-symfony1'],
}
EOF;

        return $lines;
    }
}
