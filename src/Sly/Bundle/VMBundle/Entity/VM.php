<?php

namespace Sly\Bundle\VMBundle\Entity;

use Sly\Bundle\VMBundle\Model\VM as BaseVM;

use Doctrine\ORM\Mapping as ORM;

/**
 * VM entity.
 *
 * @uses \Sly\Bundle\VMBundle\Model\VM
 * @author Cédric Dugat <cedric@dugat.me>
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
     * @ORM\Column(name="configuration", type="string", length=4000, nullable=false)
     */
    protected $configuration;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="web", type="string", length=4000, nullable=false)
     */
    protected $web;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="db", type="string", length=4000, nullable=false)
     */
    protected $db;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="phpModules", type="string", length=4000, nullable=false)
     */
    protected $phpModules;

    /**
     * {@inheritDoc}
     *
     * @ORM\Column(name="tools", type="string", length=4000, nullable=false)
     */
    protected $tools;
}
