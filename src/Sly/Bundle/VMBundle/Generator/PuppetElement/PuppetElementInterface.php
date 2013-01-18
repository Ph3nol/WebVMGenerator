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
     * To use VM entity: $this->vm.
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
     * @return array
     */
    public function getGitSubmodules();

    /**
     * Get manifest line(s).
     * 
     * @return string
     */
    public function getManifestLine();
}
