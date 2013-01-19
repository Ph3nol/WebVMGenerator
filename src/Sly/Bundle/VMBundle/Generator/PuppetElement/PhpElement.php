<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * PHP Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PhpElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'php';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) count($this->getVM()->getPhp());
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/php', 'https://github.com/example42/puppet-php.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $phpModules = $this->getVM()->getPhpModules();

        array_walk_recursive($phpModules, function(&$input) {
            $input = sprintf("'%s'", $input);
        });

        $phpModules = sprintf('[ %s ]', implode(', ', $phpModules));

        $lines = <<< EOF
class {
  'php':
      source => '/vagrant/files/php/php.ini',
}

file {
    'php5cli.config':
        path    => '/etc/php5/cli/php.ini',
        ensure  => '/vagrant/files/php/php-cli.ini',
        require => Package['php'],
}

php::module {
    $phpModules:
        require => Exec['apt-update'],
        notify  => Service['apache'],
}
EOF;

        return $lines;
    }
}
