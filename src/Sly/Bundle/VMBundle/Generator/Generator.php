<?php

namespace Sly\Bundle\VMBundle\Generator;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Bundle\GaufretteBundle\FilesystemMap;

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
     * @var \Symfony\Component\HttpFoundation\SessionInterface
     */
    private $session;

    /**
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap
     */
    private $vmFileSystem;

    /**
     * @var \Sly\Bundle\VMBundle\Config\VMCollection
     */
    private $vmCollection;

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
     * @param \Sly\Bundle\VMBundle\Config\VMCollection              $vmCollection  VM collection
     * @param \Sly\Bundle\VMBundle\Generator\GitSubmoduleCollection $gitSubmodules Git submodules collection
     * @param string                                                $kernelRootDir Kernel root directory
     */
    public function __construct(SessionInterface $session, FilesystemMap $vmFileSystem, VMCollection $vmCollection, GitSubmoduleCollection $gitSubmodules, $kernelRootDir)
    {
        $this->session         = $session;
        $this->vmFileSystem    = $vmFileSystem->get('vm');
        $this->vmCollection    = $vmCollection;
        $this->vmConfig        = $this->vmCollection->get('default');
        $this->gitSubmodules   = $gitSubmodules;
        $this->kernelRootDir   = $kernelRootDir;

        if (false === $this->session->has('generatorSessionID')) {
            $this->session->set('generatorSessionID', md5(uniqid()));
        }
    }

    /**
     * Get cache path from filesystem and session.
     * 
     * @return string
     */
    public function getCachePath()
    {
        return sprintf('%s/cache/%s',
            $this->kernelRootDir,
            $this->session->get('generatorSessionID')
        );
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
         * Generate .gitmodules file.
         */
        $this->vmFileSystem->write(
            $this->session->get('generatorSessionID').'/'.'.gitmodules',
            $this->getGitSubmodulesFileContent(),
            true
        );

        return $this->vmConfig;
    }

    /**
     * Get Git submodules file content.
     * 
     * @return string
     */
    private function getGitSubmodulesFileContent()
    {
        if ($this->vmConfig['web']['apache'] || $this->vmConfig['web']['apacheSSL']) {
            $this->gitSubmodules->add('apache');
        }

        if ((bool) count($this->vmConfig['phpModules'])) {
            $this->gitSubmodules->add('php');
        }

        return $this->gitSubmodules->getFileContent();
    }
}
