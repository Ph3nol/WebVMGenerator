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
    private $web;

    /**
     * @var array
     */
    private $db;

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

    /**
     * Get Configuration.
     *
     * @return array Configuration value
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    
    /**
     * Set Configuration.
     *
     * @param array $configuration Configuration value
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get Web.
     *
     * @return array Web value
     */
    public function getWeb()
    {
        return $this->web;
    }
    
    /**
     * Set Web.
     *
     * @param array $web Web value
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * Get Db.
     *
     * @return array Db value
     */
    public function getDb()
    {
        return $this->db;
    }
    
    /**
     * Set Db.
     *
     * @param array $db Db value
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Get PhpModules.
     *
     * @return array PhpModules value
     */
    public function getPhpModules()
    {
        return $this->phpModules;
    }
    
    /**
     * Set PhpModules.
     *
     * @param array $phpModules PhpModules value
     */
    public function setPhpModules($phpModules)
    {
        $this->phpModules = $phpModules;
    }

    /**
     * Get Tools.
     *
     * @return array Tools value
     */
    public function getTools()
    {
        return $this->tools;
    }
    
    /**
     * Set Tools.
     *
     * @param array $tools Tools value
     */
    public function setTools($tools)
    {
        $this->tools = $tools;
    }
}
