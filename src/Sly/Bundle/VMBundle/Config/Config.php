<?php

namespace Sly\Bundle\VMBundle\Config;

use Symfony\Component\DependencyInjection\Exception\RuntimeException,
    Symfony\Component\Yaml\Yaml
;

/**
 * Configuration.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Config
{
    const DEFAULT_CONFIG_NAME = 'default';

    /**
     * @var array
     */
    private $config;

    /**
     * @var \Sly\Bundle\VMBundle\Config\VMCollection
     */
    private $vmConfigs;

    /**
     * @var string
     */
    private $vmConfigName;

    /**
     * Constructor.
     *
     * @param array                                    $config    Configuration
     * @param \Sly\Bundle\VMBundle\Config\VMCollection $vmConfigs VM configurations collection
     */
    public function __constuct(array $config, VMCollection $vmConfigs)
    {
        $this->config       = $config;
        $this->vmConfigs    = $vmConfigs;
        $this->vmConfigName = self::DEFAULT_CONFIG_NAME;

        if (array_key_exists(self::DEFAULT_CONFIG_NAME, $this->config['configurations'])) {
            throw new RuntimeException('No VM "default" configuration name allowed into your configuration');
        }

        $this->vmConfigs->add(self::DEFAULT_CONFIG_NAME, $this->getVMDefaultConfig());

        foreach ($this->config['configurations'] as $vmKey => $vmConfig) {
            $vmConfig->setConfiguration($vmKey);

            $this->vmConfigs->add($vmKey, $vmConfig);
        }
    }

    /**
     * Get VMConfig value.
     *
     * @return array VMConfig value to get
     */
    public function getVMConfig()
    {
        if (false === $this->vmConfigs instanceof VMCollection) {
            return array();
        }

        return array_merge(
            $this->vmConfigs->get(self::DEFAULT_CONFIG_NAME),
            $this->vmConfigs->get($this->vmConfigName)
        );
    }
    
    /**
     * Set VmConfigName value.
     *
     * @param string $vmConfigName VmConfigName value to set
     *
     * @return \Sly\Bundle\VMBundle\Config\Config
     */
    public function setVMConfigName($vmConfigName)
    {
        if ($this->vmConfigs->has($vmConfigName)) {
            $this->vmConfigName = $vmConfigName;
        }

        return $this;
    }

    /**
     * Get VM defaults.
     * 
     * @return array
     */
    public static function getVMDefaultConfig()
    {
        return Yaml::parse(
            file_get_contents(
                __DIR__.'/../Resources/config/vm/defaultConfig.yml'
            )
        );
    }

    /**
     * Get Vagrant boxes.
     * 
     * @return array
     */
    public static function getVagrantBoxes()
    {
        return Yaml::parse(
            file_get_contents(
                __DIR__.'/../Resources/config/vm/vagrantBoxes.yml'
            )
        );
    }
}
