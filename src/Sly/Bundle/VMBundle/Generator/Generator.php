<?php

namespace Sly\Bundle\VMBundle\Generator;

use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Lootils\Archiver\TarArchive;
use Doctrine\Common\Util\Inflector;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementCollection,
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
     * @var \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementCollection
     */
    private $puppetElements;

    /**
     * @var \Sly\Bundle\VMBundle\Entity\VM
     */
    private $vm;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * Constructor.
     *
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap                            $vmFileSystem   Gaufrette VM file system
     * @param \Sly\Bundle\VMBundle\Config\Config                                   $config         Config
     * @param \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementCollection $puppetElements Puppet elements collection
     * @param string                                                               $kernelRootDir  Kernel root directory
     */
    public function __construct(FilesystemMap $vmFileSystem, Config $config, PuppetElementCollection $puppetElements, $kernelRootDir)
    {
        $this->vmFileSystem   = $vmFileSystem->get('vm');
        $this->config         = $config;
        $this->puppetElements = $puppetElements;
        $this->kernelRootDir  = $kernelRootDir;
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
     * @return string
     */
    public function getArchivePath()
    {
        return sprintf(
            '%s/%s.tar',
            $this->getCachePath(false),
            $this->vm->getUKey()
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
            Inflector::classify($this->vm->getName())
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
     * Set Vm value.
     *
     * @param \Sly\Bundle\VMBundle\Entity\VM $vm Vm value to set
     *
     * @return \Sly\Bundle\VMBundle\Entity\VM
     */
    public function setVM($vm)
    {
        $this->vm = $vm;

        return $this->vm;
    }

    /**
     * Generate.
     *
     * @param \Sly\Bundle\VMBundle\Entity\VM $vm Virtual Machine
     * 
     * @return \Sly\Bundle\VMBundle\Entity\VM
     */
    public function generate(VM $vm)
    {
        $this->vm = $vm;

        $this->generateGitSubmodulesFile();
        $this->generateOtherFiles();
        $this->generateArchiveFromFiles();

        return $this->vm;
    }

    /**
     * Generate other files.
     */
    private function generateOtherFiles()
    {
        $this->vmFileSystem->write(
            $this->vm->getUKey().'/README',
            'README',
            true
        );
    }

    /**
     * Generate Git submodules file.
     */
    private function generateGitSubmodulesFile()
    {
        $gitSubmodulesContent = array();

        foreach ($this->puppetElements as $puppetElement) {
            $puppetElement->setVM($this->vm);

            if ($puppetElement->getCondition()) {
                $gitSubmodulesContent[] = $puppetElement->getGitSubmodulesContent();
            }
        }

        if ((bool) count($gitSubmodulesContent)) {
            $gitSubmodulesContent = implode('', $gitSubmodulesContent);

            $this->vmFileSystem->write(
                $this->vm->getUKey().'/'.'.gitmodules',
                $gitSubmodulesContent,
                true
            );
        }
    }

    /**
     * Generate archive from files.
     */
    private function generateArchiveFromFiles()
    {
        $vmArchive = new TarArchive(
            sprintf('%s/%s.tar', $this->getCachePath(false), $this->vm->getUKey())
        );

        $vmArchive->add($this->getCachePath());
    }
}
