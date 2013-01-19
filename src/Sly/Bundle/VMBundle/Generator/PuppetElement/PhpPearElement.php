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
        return (bool) ($this->getVM()->getPhp() && count($this->getVM()->getPhpPearComponents()));
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $lines = <<< EOF
class { "pear": }

pear::channel { "phpunit":
    url => "pear.phpunit.de",
}

pear::channel { "symfony2":
    url     => "pear.symfony.com",
    require => Exec["pear-channel-phpunit"],
}

pear::channel { "symfony1":
    url     => "pear.symfony-project.com",
    require => Exec["pear-channel-symfony2"],
}

pear::channel { "components":
    url     => "components.ez.no",
    require => Exec["pear-channel-symfony1"],
}
EOF;

        if (in_array('phpunit', $this->getVM()->getPhpPearComponents())) {
            $lines .= <<< EOF
\n
pear::module { "phpunit":
    channel_name => "phpunit",
    package_name => "PHPUnit",
    dir_source   => "PHPUnit",
    require      => Exec["pear-channel-components"],
}
EOF;
        }

        if (in_array('phpcodesniffer', $this->getVM()->getPhpPearComponents())) {
            $lines .= <<< EOF
\n
pear::module { "php_code_sniffer":
    package_name => "PHP_CodeSniffer",
    dir_source   => "PHP/CodeSniffer",
    require      => Class["pear"],
}
EOF;
        }

        return $lines;
    }
}
