<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Entity\VM,
    Sly\Bundle\VMBundle\Generator\Generator
;

/**
 * BasePuppetElement.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
abstract class BasePuppetElement
{
    /**
     * @var \Sly\Bundle\VMBundle\Config\Config
     */
    protected $config;

    /**
     * @var \Sly\Bundle\VMBundle\Generator\Generator
     */
    protected $generator;

    /**
     * Set configuration.
     * 
     * @param \Sly\Bundle\VMBundle\Config\Config $config Configuration
     *
     * @return \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get Generator value.
     *
     * @return \Sly\Bundle\VMBundle\Generator\Generator Generator value to get
     */
    public function getGenerator()
    {
        return $this->generator;
    }
    
    /**
     * Set Generator value.
     *
     * @param \Sly\Bundle\VMBundle\Generator\Generator $generator Generator value to set
     */
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Get virtual machine.
     *
     * @return \Sly\Bundle\VMBundle\Entity\VM
     */
    public function getVM()
    {
        return $this->generator->getVM();
    }

    /**
     * @see \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
     */
    public function getGitModules()
    {
        return array();
    }

    /**
     * @see \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
     */
    public function getManifestLines()
    {
        return null;
    }

    /**
     * Get manifest lines.
     * 
     * @return null|string
     */
    public function getManifestContent()
    {
        if ($this->getManifestLines()) {
            return $this->getManifestLines()."\n\n"; 
        }

        return null;
    }

    /**
     * Post process event.
     */
    public function postProcess()
    {
    }
}
