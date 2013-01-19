<?php

namespace Sly\Bundle\VMBundle\Model;

use Sly\Bundle\VMBundle\Config\Config;
use Doctrine\Common\Util\Inflector;

/**
 * VM model.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VM
{
    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $uKey;

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
     * @var boolean
     */
    protected $php;

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
    protected $mysql;

    /**
     * @var string
     */
    protected $mysqlRootPassword;

    /**
     * @var boolean
     */
    protected $vim;

    /**
     * @var boolean
     */
    protected $vimConfig;

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
        $this->uKey      = md5(uniqid().time());
        $vmDefaultConfig = Config::getVMDefaultConfig();

        foreach ($vmDefaultConfig as $key => $value) {
            $setter = 'set'.$key;

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * __toString.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();

    }

    /**
     * Get cache path.
     *
     * @param null|string $prefixPath Prefix path
     * 
     * @return string
     */
    public function getCachePath($prefixPath = null)
    {
        return sprintf(
            '%scache/vm/%s',
            $prefixPath ? $prefixPath.'/' : '',
            $this->getUKey()
        );
    }

    /**
     * Get cache path.
     *
     * @param boolean $internalFileName Internal filename
     * 
     * @return string
     */
    public function getArchiveFilename($internalFileName = false)
    {
        return sprintf(
            '%s.tar',
            $internalFileName ? $this->getUKey() : Inflector::classify((string) $this)
        );
    }

    /**
     * Get archive path.
     *
     * @param null|string $prefixPath Prefix path
     * @param boolean     $internal   Internal filename
     * 
     * @return string
     */
    public function getArchivePath($prefixPath = null, $internal = false)
    {
        return sprintf(
            '%scache/vm/%s/%s',
            $prefixPath ? $prefixPath.'/' : '',
            $this->getUKey(),
            $internal ? '/../'.$this->getArchiveFilename(true) : $this->getArchiveFilename()
        );
    }

    /**
     * Get Id value.
     *
     * @return integer Id value to get
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get UKey value.
     *
     * @return string UKey value to get
     */
    public function getUKey()
    {
        return $this->uKey;
    }
    
    /**
     * Set UKey value.
     *
     * @param string $uKey UKey value to set
     */
    public function setUKey($uKey)
    {
        $this->uKey = $uKey;
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
     * Get Mysql value.
     *
     * @return boolean Mysql value to get
     */
    public function getMysql()
    {
        return $this->mysql;
    }
    
    /**
     * Set Mysql value.
     *
     * @param boolean $mysql Mysql value to set
     */
    public function setMysql($mysql)
    {
        $this->mysql = $mysql;
    }

    /**
     * Get MysqlRootPassword value.
     *
     * @return string MysqlRootPassword value to get
     */
    public function getMysqlRootPassword()
    {
        return $this->mysqlRootPassword;
    }
    
    /**
     * Set MysqlRootPassword value.
     *
     * @param string $mysqlRootPassword MysqlRootPassword value to set
     */
    public function setMysqlRootPassword($mysqlRootPassword)
    {
        $this->mysqlRootPassword = $mysqlRootPassword;
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
     * Get Php value.
     *
     * @return boolean Php value to get
     */
    public function getPhp()
    {
        return $this->php;
    }
    
    /**
     * Set Php value.
     *
     * @param boolean $php Php value to set
     */
    public function setPhp($php)
    {
        $this->php = $php;
    }

    /**
     * Get PhpModules value.
     *
     * @return array PhpModules value to get
     */
    public function getPhpModules()
    {
        return $this->phpModules;
    }
    
    /**
     * Set PhpModules value.
     *
     * @param array $phpModules PhpModules value to set
     */
    public function setPhpModules($phpModules)
    {
        $this->phpModules = $phpModules;
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
     * Get VimConfig value.
     *
     * @return boolean VimConfig value to get
     */
    public function getVimConfig()
    {
        return $this->vimConfig;
    }
    
    /**
     * Set VimConfig value.
     *
     * @param boolean $vimConfig VimConfig value to set
     */
    public function setVimConfig($vimConfig)
    {
        $this->vimConfig = $vimConfig;
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
