<?php

namespace Sly\Bundle\VMBundle\Entity;

use Sly\Bundle\VMBundle\Model\VM as BaseVM;

use Doctrine\ORM\Mapping as ORM;

/**
 * VM entity.
 *
 * @uses \Sly\Bundle\VMBundle\Model\VM
 * @author Cédric Dugat <cedric@dugat.me>
 *
 * @ORM\Table(name="vm")
 * @ORM\Entity(repositoryClass="Sly\Bundle\VMBundle\Entity\VMRepository")
 */
class VM extends BaseVM
{
    /**
     * {@inheritDoc}
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="u_key", type="string", length=32)
     */
    protected $uKey;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="configuration", type="string", length=30)
     */
    protected $configuration;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="vagrant_box", type="string", length=30)
     */
    protected $vagrantBox;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="vagrant_nfs", type="boolean")
     */
    protected $vagrantNFS;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="vagrant_memory", type="integer", length=4)
     */
    protected $vagrantMemory;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="vagrant_cpu", type="integer", length=2)
     */
    protected $vagrantCpu;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="vagrant_final_launch", type="boolean")
     */
    protected $vagrantFinalLaunch;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="hostname", type="string", length=150)
     */
    protected $hostname;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="ip", type="string", length=50)
     */
    protected $ip;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="timezone", type="string", length=50)
     */
    protected $timezone;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="apache", type="boolean")
     */
    protected $apache;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="apache_port", type="integer", length=5)
     */
    protected $apachePort;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="apache_root_dir", type="string", length=150)
     */
    protected $apacheRootDir;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="apache_ssl", type="boolean")
     */
    protected $apacheSSL;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php", type="boolean")
     */
    protected $php;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php_xdebug_max_nesting_level", type="integer", length=5)
     */
    protected $phpXDebugMaxNestingLevel;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php_version", type="string", length=10)
     */
    protected $phpVersion;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php_pear_components", type="array")
     */
    protected $phpPearComponents;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php_my_admin", type="boolean")
     */
    protected $phpMyAdmin;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php_modules", type="array")
     */
    protected $phpModules;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="nginx", type="boolean")
     */
    protected $nginx;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="varnish", type="boolean")
     */
    protected $varnish;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="mysql", type="boolean")
     */
    protected $mysql;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="mysql_root_password", type="string", length=50)
     */
    protected $mysqlRootPassword;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="system_packages", type="array")
     */
    protected $systemPackages;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="mailcatcher", type="boolean")
     */
    protected $mailCatcher;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="vim_config", type="boolean")
     */
    protected $vimConfig;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="composer", type="boolean")
     */
    protected $composer;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="oh_my_zsh", type="boolean")
     */
    protected $ohMyZsh;
}
