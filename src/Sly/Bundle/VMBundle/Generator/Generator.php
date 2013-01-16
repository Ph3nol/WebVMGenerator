<?php

namespace Sly\Bundle\VMBundle\Generator;

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
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap
     */
    private $vmFileSystem;

    /**
     * @var \Sly\Bundle\VMBundle\Config\Config
     */
    private $config;

    /**
     * @var \Sly\Bundle\VMBundle\Entity\VM
     */
    private $vm;

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
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap             $vmFileSystem  Gaufrette VM file system
     * @param \Sly\Bundle\VMBundle\Config\Config                    $config        Config
     * @param \Sly\Bundle\VMBundle\Generator\GitSubmoduleCollection $gitSubmodules Git submodules collection
     * @param string                                                $kernelRootDir Kernel root directory
     */
    public function __construct(FilesystemMap $vmFileSystem, Config $config, GitSubmoduleCollection $gitSubmodules, $kernelRootDir)
    {
        $this->vmFileSystem  = $vmFileSystem->get('vm');
        $this->config        = $config;
        $this->gitSubmodules = $gitSubmodules;
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * Get cache path from filesystem and VM uKey.
     *
     * @param boolean $complete Complete path (with VM uKey last directory)
     * 
     * @return string
     */
    public function getCachePath($complete = true)
    {
        return sprintf(
            '%s/cache/vm%s',
            $this->kernelRootDir,
            $complete ? '/'.$this->vm->getUKey() : null
        );
    }

    /**
     * Get archive path.
     *
     * @param string $uKey VM uKey
     * 
     * @return string
     */
    public function getArchivePath($uKey = null)
    {
        return sprintf(
            '%s/%s.tar',
            $this->getCachePath(false),
            $uKey ? $uKey : $this->vm->getUKey()
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
     * Get VM.
     * 
     * @return \Sly\Bundle\VMBundle\Entity\VM
     */
    public function getVM()
    {
        return $this->vm;
    }

    /**
     * Generate.
     *
     * @param \Sly\Bundle\VMBundle\Entity\VM $vm Virtual Machine
     * 
     * @return array
     */
    public function generate(VM $vm)
    {
        $this->vm = $vm;

        /**
         * README file generation.
         */
        $this->vmFileSystem->write(
            $this->vm->getUKey().'/README',
            'README',
            true
        );

        /**
         * Generate .gitmodules file.
         */
        $this->vmFileSystem->write(
            $this->vm->getUKey().'/'.'.gitmodules',
            $this->getGitSubmodulesFileContent(),
            true
        );

        /**
         * Generate TAR archive.
         */
        $vmArchive = new TarArchive(
            sprintf('%s/%s.tar', $this->getCachePath(false), $this->vm->getUKey())
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
        if ($this->vm->getApache() || $this->vm->getApacheSSL()) {
            $this->gitSubmodules->add('apache');
        }

        if ((bool) count($this->vm->getPhpModules())) {
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
