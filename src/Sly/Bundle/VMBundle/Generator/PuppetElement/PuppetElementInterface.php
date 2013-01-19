<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * PuppetElement interface.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
interface PuppetElementInterface
{
    /**
     * Get name.
     *
     * Has to match with a VM option.
     * 
     * @return string
     */
    public function getName();

    /**
     * Get VM condition to apply Puppet Element.
     *
     * Your logic (depending or not on VM), making condition to be applied from Generator.
     * You can access the Generator service with $this->getGenerator().
     * Through it, you will access the VM, the Symfony filesystem etc.
     * See \Sly\Bundle\VMBundle\Generator\Generator for more informations.
     * 
     * @return boolean
     */
    public function getCondition();

    /**
     * Get Git submodules.
     *
     * Example:
     * 
     * return array(
     *     array('path/to/modules/package1', 'https://github.com/example/puppet-package1.git'),
     *     array('path/to/modules/package2', 'https://github.com/example/puppet-package2.git'),
     * );
     *
     * If no Git submodules are needed, just return an empty array.
     * 
     * @return array
     */
    public function getGitSubmodules();

    /**
     * Get manifest line(s).
     * 
     * @return string|null
     */
    public function getManifestLines();
}
