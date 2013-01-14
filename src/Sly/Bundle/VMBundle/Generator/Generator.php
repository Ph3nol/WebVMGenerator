<?php

namespace Sly\Bundle\VMBundle\Generator;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Config\VMCollection
;

/**
 * Generator.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Generator
{
    /**
     * @var \Sly\Bundle\VMBundle\Config\VMCollection
     */
    private $vmCollection;

    /**
     * @var array
     */
    private $vmConfig;

    /**
     * Constructor.
     *
     * @param \Sly\Bundle\VMBundle\Config\VMCollection $vmCollection VM collection
     */
    public function __construct(VMCollection $vmCollection)
    {
        $this->vmCollection = $vmCollection;
        $this->vmConfig     = $this->vmCollection->get('default');
    }

    /**
     * Update vmConfig.
     *
     * @param array $config Confguration
     *
     * @return array
     */
    public function updateVMConfig(array $config)
    {
        $this->vmConfig = array_merge($this->vmConfig, $config);

        return $this->vmConfig;
    }

    /**
     * Get VMConfig.
     * 
     * @return array
     */
    public function getVMConfig()
    {
        return $this->vmConfig;
    }

    /**
     * Generate.
     * 
     * @return array
     */
    public function generate()
    {
        /**
         * @todo
         */
        $gitSubmodulesFileContent = $this->getGitSubmodulesFileContent();
    }

    /**
     * Get Git submodules file content.
     * 
     * @return string
     */
    private function getGitSubmodulesFileContent()
    {
        $gitSubmodules = new GitSubmoduleCollection();

        if ($this->vmConfig['web']['apache'] || $this->vmConfig['web']['apacheSSL']) {
            $gitSubmodules->add('apache');
        }

        if ((bool) count($this->vmConfig['phpModules'])) {
            $gitSubmodules->add('php');
        }

        return $gitSubmodules->getFileContent();
    }
}
