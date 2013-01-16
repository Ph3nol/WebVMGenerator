<?php

namespace Sly\Bundle\VMBundle\Generator;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Lootils\Archiver\TarArchive;
use Doctrine\Common\Util\Inflector;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Entity\VM
;

/**
 * Generator.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Generator
{
    /**
     * @var \Symfony\Component\HttpFoundation\SessionInterface
     */
    private $session;

    /**
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap
     */
    private $vmFileSystem;

    /**
     * @var \Sly\Bundle\VMBundle\Config\Config
     */
    private $config;

    /**
     * @var array
     */
    private $vmConfig;

    /**
     * @var \Sly\Bundle\VMBundle\Generator\GitSubmoduleCollection
     */
    private $gitSubmodules;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\HttpFoundation\SessionInterface    $session       Session
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap             $vmFileSystem  Gaufrette VM file system
     * @param \Sly\Bundle\VMBundle\Config\Config                    $config        Config
     * @param \Sly\Bundle\VMBundle\Generator\GitSubmoduleCollection $gitSubmodules Git submodules collection
     * @param string                                                $kernelRootDir Kernel root directory
     */
    public function __construct(SessionInterface $session, FilesystemMap $vmFileSystem, Config $config, GitSubmoduleCollection $gitSubmodules, $kernelRootDir)
    {
        $this->session         = $session;
        $this->vmFileSystem    = $vmFileSystem->get('vm');
        $this->config          = $config;
        $this->vmConfig        = $config->getVMConfig();
        $this->gitSubmodules   = $gitSubmodules;
        $this->kernelRootDir   = $kernelRootDir;

        if (false === $this->session->has('generatorSessionID')) {
            $this->session->set('generatorSessionID', md5(uniqid()));
        }
    }

    /**
     * Get cache path from filesystem and session.
     *
     * @param boolean $complete Complete path (with session id last directory)
     * 
     * @return string
     */
    public function getCachePath($complete = true)
    {
        return sprintf(
            '%s/cache/vm%s',
            $this->kernelRootDir,
            $complete ? '/'.$this->session->get('generatorSessionID') : null
        );
    }

    /**
     * Get archive path.
     *
     * @param string $sessionID Specific session ID
     * 
     * @return string
     */
    public function getArchivePath($sessionID = null)
    {
        return sprintf(
            '%s/%s.tar',
            $this->getCachePath(false),
            $sessionID ? $sessionID : $this->session->get('generatorSessionID')
        );
    }

    /**
     * Get archive filename.
     *
     * @return string
     */
    public function getArchiveFilename()
    {
        return sprintf(
            '%s-VagrantConfig.tar',
            Inflector::classify($this->vmConfig['name'])
        );
    }

    /**
     * Generate.
     *
     * @param \Sly\Bundle\VMBundle\Entity\VM|array $customConfig Custom configuration
     * 
     * @return array
     */
    public function generate($customConfig = array())
    {
        if ($customConfig instanceof VM) {
            $customConfig = self::convertEntityToArray($customConfig);
        }

        $this->vmConfig = array_merge($this->vmConfig, $customConfig);

        /**
         * README file generation.
         */
        $this->vmFileSystem->write(
            $this->session->get('generatorSessionID').'/README',
            'README',
            true
        );

        /**
         * Generate .gitmodules file.
         */
        $this->vmFileSystem->write(
            $this->session->get('generatorSessionID').'/'.'.gitmodules',
            $this->getGitSubmodulesFileContent(),
            true
        );

        /**
         * Generate TAR archive.
         */
        $vmArchive = new TarArchive(
            sprintf('%s/%s.tar', $this->getCachePath(false), $this->session->get('generatorSessionID'))
        );

        $vmArchive->add($this->getCachePath());
    }

    /**
     * Get Git submodules file content.
     * 
     * @return string
     */
    private function getGitSubmodulesFileContent()
    {
        if ($this->vmConfig['apache'] || $this->vmConfig['apacheSSL']) {
            $this->gitSubmodules->add('apache');
        }

        if ((bool) count($this->vmConfig['phpModules'])) {
            $this->gitSubmodules->add('php');
        }

        return $this->gitSubmodules->getFileContent();
    }

    /**
     * convertEntityToArray.
     * 
     * @param \Sly\Bundle\VMBundle\Entity\VM $entity Entity
     * 
     * @return array
     */
    private static function convertEntityToArray(VM $entity)
    {
        $reflectionClass = new \ReflectionClass($entity);
        $arrayResult     = array();

        foreach ($reflectionClass->getProperties() as $p) {
            $getter = 'get'.ucfirst($p->name);

            if (method_exists($entity, $getter)) {
                $arrayResult[$p->name] = $entity->$getter();
            }
        }

        return $arrayResult;
    }
}
