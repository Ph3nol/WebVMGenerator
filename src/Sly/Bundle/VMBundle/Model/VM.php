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
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var boolean
     */
    protected $apache;

    /**
     * @var boolean
     */
    protected $apacheSSL;

    /**
     * @var array
     */
    protected $phpModules;

    /**
     * @var boolean
     */
    protected $nginx;

    /**
     * @var boolean
     */
    protected $varnish;

    /**
     * @var boolean
     */
    protected $vim;

    /**
     * @var boolean
     */
    protected $git;

    /**
     * @var boolean
     */
    protected $composer;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get Name value.
     *
     * @return string Name value to get
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set Name value.
     *
     * @param string $name Name value to set
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get Hostname value.
     *
     * @return string Hostname value to get
     */
    public function getHostname()
    {
        return $this->hostname;
    }
    
    /**
     * Set Hostname value.
     *
     * @param string $hostname Hostname value to set
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * Get Ip value.
     *
     * @return string Ip value to get
     */
    public function getIp()
    {
        return $this->ip;
    }
    
    /**
     * Set Ip value.
     *
     * @param string $ip Ip value to set
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get Timezone value.
     *
     * @return string Timezone value to get
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
    
    /**
     * Set Timezone value.
     *
     * @param string $timezone Timezone value to set
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * Get Apache value.
     *
     * @return boolean Apache value to get
     */
    public function getApache()
    {
        return $this->apache;
    }
    
    /**
     * Set Apache value.
     *
     * @param boolean $apache Apache value to set
     */
    public function setApache($apache)
    {
        $this->apache = $apache;
    }

    /**
     * Get ApacheSSL value.
     *
     * @return boolean ApacheSSL value to get
     */
    public function getApacheSSL()
    {
        return $this->apacheSSL;
    }
    
    /**
     * Set ApacheSSL value.
     *
     * @param boolean $apacheSSL ApacheSSL value to set
     */
    public function setApacheSSL($apacheSSL)
    {
        $this->apacheSSL = $apacheSSL;
    }

    /**
     * Get Nginx value.
     *
     * @return boolean Nginx value to get
     */
    public function getNginx()
    {
        return $this->nginx;
    }
    
    /**
     * Set Nginx value.
     *
     * @param boolean $nginx Nginx value to set
     */
    public function setNginx($nginx)
    {
        $this->nginx = $nginx;
    }

    /**
     * Get Varnish value.
     *
     * @return boolean Varnish value to get
     */
    public function getVarnish()
    {
        return $this->varnish;
    }
    
    /**
     * Set Varnish value.
     *
     * @param boolean $varnish Varnish value to set
     */
    public function setVarnish($varnish)
    {
        $this->varnish = $varnish;
    }

    /**
     * Get Vim value.
     *
     * @return boolean Vim value to get
     */
    public function getVim()
    {
        return $this->vim;
    }
    
    /**
     * Set Vim value.
     *
     * @param boolean $vim Vim value to set
     */
    public function setVim($vim)
    {
        $this->vim = $vim;
    }

    /**
     * Get Git value.
     *
     * @return boolean Git value to get
     */
    public function getGit()
    {
        return $this->git;
    }
    
    /**
     * Set Git value.
     *
     * @param boolean $git Git value to set
     */
    public function setGit($git)
    {
        $this->git = $git;
    }

    /**
     * Get Composer value.
     *
     * @return boolean Composer value to get
     */
    public function getComposer()
    {
        return $this->composer;
    }
    
    /**
     * Set Composer value.
     *
     * @param boolean $composer Composer value to set
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;
    }
}
