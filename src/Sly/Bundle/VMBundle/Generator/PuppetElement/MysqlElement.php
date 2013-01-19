<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Mysql Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class MysqlElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mysql';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getMySQL();
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/mysql', 'https://github.com/example/puppet-mysql.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $mysqlPassword = $this->getVM()->getMysqlRootPassword()
            ? $this->getVM()->getMysqlRootPassword()
            : ''
        ;

        $lines = <<< EOF
class { 'mysql':
        root_password => '$mysqlPassword',
        require       => Exec['apt-update'],
}
EOF;

        return $lines;
    }
}
