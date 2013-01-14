<?php

namespace Sly\Bundle\VMBundle\Model;

/**
 * VM model.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VM
{
    /**
     * @var array
     */    
    private $configuration;

    /**
     * @var array
     */
    private $phpModules;

    /**
     * @var array
     */
    private $tools;

    /**
     * Constructor.
     *
     * @param array  $configuration Configuration
     * @param string $configName    Configuration name
     */
    public function __construct($configuration, $configName = null)
    {
        if ($configName && isset($configuration['configurations'][$configName])) {
            $vmConfiguration = $configuration['configurations'][$configName];
        } elseif ((bool) count($configuration)) {
            $confNames       = array_keys($configuration);
            $vmConfiguration = $configuration[$confNames[0]];
        }

        $this->configuration = $vmConfiguration['configuration'];
        $this->phpModules    = $vmConfiguration['phpModules'];
        $this->tools         = $vmConfiguration['tools'];
    }
}
