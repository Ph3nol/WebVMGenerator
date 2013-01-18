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
     * Get Git submodules content.
     * 
     * @return string
     */
    public function getGitSubmodulesContent()
    {
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
}
