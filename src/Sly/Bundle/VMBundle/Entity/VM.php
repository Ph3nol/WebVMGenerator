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
     * @ORM\Column(name="apache_ssl", type="boolean")
     */
    protected $apacheSSL;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="php_modules", type="string", length=4000)
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
     * @ORM\Column(name="vim", type="boolean")
     */
    protected $vim;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="git", type="boolean")
     */
    protected $git;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="composer", type="boolean")
     */
    protected $composer;
}
