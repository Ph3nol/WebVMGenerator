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
        return (bool) $this->getVM()->getPhp();
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
        $withAPC    = false;

        if (in_array('apc', $phpModules)) {
            $withAPC = true;
            unset($phpModules['apc']);
        }

        array_walk_recursive($phpModules, function(&$input) {
            $input = sprintf('"%s"', $input);
        });

        $phpModules = sprintf('[ %s ]', implode(', ', $phpModules));

        $lines = <<< EOF
class { "php":
    source => "/vagrant/files/php/php.ini",
}

file { "php5cli.config":
    path    => "/etc/php5/cli/php.ini",
    ensure  => "/vagrant/files/php/php-cli.ini",
    require => Package["php"],
}

php::module { $phpModules:
    require => Exec["apt-update"],
    notify  => Service["apache"],
}
EOF;

        if ($withAPC) {
            $lines .= <<< EOF
\n
php::module { "apc":
    module_prefix => "php-",
    require       => Exec["apt-update"],
    notify        => Service["apache"],
}
EOF;
        }

        if ($this->getVM()->getPhpMyAdmin()) {
            $lines .= <<< EOF
\n
system::package { "phpmyadmin":
    require => Package["php"]
}
EOF;
        }

        return $lines;
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess()
    {
        $phpFilesPath = sprintf(
            '%s/%s/files/php',
            $this->getGenerator()->getKernelRootDir(),
            $this->getVM()->getCachePath()
        );

        $xdebugMaxNestingLevel = $this->getVM()->getPhpXDebugMaxNestingLevel();

        if (in_array('xdebug', $this->getVM()->getPhpModules())) {
            $phpIniContent    = file_get_contents($phpFilesPath.'/php.ini');
            $phpIniCliContent = file_get_contents($phpFilesPath.'/php-cli.ini');

            $xdebugLines = <<< EOF
\n
[xdebug]
xdebug.max_nesting_level = $xdebugMaxNestingLevel
\n
EOF;
            file_put_contents($phpFilesPath.'/php.ini', $phpIniContent.$xdebugLines);
            file_put_contents($phpFilesPath.'/php-cli.ini', $phpIniCliContent.$xdebugLines);
        }
    }
}
