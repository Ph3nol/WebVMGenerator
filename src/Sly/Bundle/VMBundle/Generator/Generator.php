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
     * Constructor.
     *
     * @param \Symfony\Component\HttpFoundation\SessionInterface    $session       Session
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap             $vmFileSystem  Gaufrette VM file system
     * @param \Sly\Bundle\VMBundle\Config\VMCollection              $vmCollection  VM collection
     * @param \Sly\Bundle\VMBundle\Generator\GitSubmoduleCollection $gitSubmodules Git submodules collection
     */
    public function __construct(SessionInterface $session, FilesystemMap $vmFileSystem, VMCollection $vmCollection, GitSubmoduleCollection $gitSubmodules)
    {
        $this->session         = $session;
        $this->vmFileSystem    = $vmFileSystem->get('vm');
        $this->vmCollection    = $vmCollection;
        $this->vmConfig        = $this->vmCollection->get('default');
        $this->gitSubmodules   = $gitSubmodules;

        if (false === $this->session->has('generatorSessionID')) {
            $this->session->set('generatorSessionID', md5(uniqid()));
        }
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
