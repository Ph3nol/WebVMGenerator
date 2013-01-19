<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Entity\VM
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
     * @var \Sly\Bundle\VMBundle\Entity\VM
     */
    protected $vm;

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
     * Get virtual machine.
     *
     * @return \Sly\Bundle\VMBundle\Entity\VM
     */
    public function getVM()
    {
        return $this->vm;
    }

    /**
     * Set virtual machine.
     * 
     * @param \Sly\Bundle\VMBundle\Entity\VM $vm Virtual Machine
     *
     * @return \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
     */
    public function setVM(VM $vm)
    {
        $this->vm = $vm;

        return $this;
    }

    /**
     * @see \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
     */
    public function getGitSubmodules()
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
     * Get Git submodules content.
     * 
     * @return null|string
     */
    public function getGitSubmodulesContent()
    {
        if ($this->getGitSubmodules() && is_array($this->getGitSubmodules()) && (bool) count($this->getGitSubmodules())) {
            $lines = array();

            foreach ($this->getGitSubmodules() as $submodule) {
                list($path, $url) = $submodule;

                $lines[] = sprintf(
                    "[submodule \"%s\"]\n    path = %s\n    url = %s\n\n",
                    $path,
                    $path,
                    $url
                );
            }

            return implode('', $lines);
        }

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
}
