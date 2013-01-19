<?php

namespace Sly\Bundle\VMBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Lootils\Archiver\TarArchive;

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
    const VAGRANT_SKELETON_PATH     = '/Resources/skeleton/vagrant';
    const PUPPET_BASE_MANIFEST_FILE = '/manifests/app.pp';
    const GIT_MODULES_FILE          = '/.gitmodules';

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

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
     * @param \Symfony\Component\Filesystem\Filesystem                             $filesystem     Filesystem
     * @param \Sly\Bundle\VMBundle\Config\Config                                   $config         Config
     * @param \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementCollection $puppetElements Puppet elements collection
     * @param string                                                               $kernelRootDir  Kernel root directory
     */
    public function __construct(Filesystem $filesystem, Config $config, PuppetElementCollection $puppetElements, $kernelRootDir)
    {
        $this->filesystem     = $filesystem;
        $this->config         = $config;
        $this->puppetElements = $puppetElements;
        $this->kernelRootDir  = $kernelRootDir;
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
     * @return \Sly\Bundle\VMBundle\Generator\Generator
     */
    public function setVM($vm)
    {
        $this->vm = $vm;

        return $this;
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
        $this->setVM($vm);

        $this->filesystem->mirror(
            __DIR__.'/..'.self::VAGRANT_SKELETON_PATH,
            $this->getVM()->getCachePath($this->kernelRootDir),
            null,
            array('override' => true)
        );

        $this->generateGitSubmodulesFile();
        $this->generatePuppetBaseFile();
        $this->generateArchiveFromFiles();

        $this->filesystem->remove($this->getVM()->getCachePath($this->kernelRootDir));

        return $this->getVM();
    }

    /**
     * Generate Git submodules file.
     */
    private function generateGitSubmodulesFile()
    {
        $gitSubmodulesContent = array();

        foreach ($this->puppetElements as $puppetElement) {
            $puppetElement->setVM($this->getVM());

            if ($puppetElement->getCondition() && $puppetElement->getGitSubmodulesContent()) {
                $gitSubmodulesContent[] = $puppetElement->getGitSubmodulesContent();
            }
        }

        if ((bool) count($gitSubmodulesContent)) {
            $gitSubmodulesContent = implode('', $gitSubmodulesContent);

            file_put_contents(
                $this->getVM()->getCachePath($this->kernelRootDir).self::GIT_MODULES_FILE,
                $gitSubmodulesContent
            );
        }
    }

    /**
     * Generate Puppet base file.
     */
    private function generatePuppetBaseFile()
    {
        $puppetBaseFileContent = array();

        foreach ($this->puppetElements as $puppetElement) {
            $puppetElement->setVM($this->getVM());

            if ($puppetElement->getCondition() && $puppetElement->getManifestContent()) {
                $puppetBaseFileContent[] = $puppetElement->getManifestContent();
            }
        }

        if ((bool) count($puppetBaseFileContent)) {
            $puppetBaseFileContent = implode('', $puppetBaseFileContent);

            file_put_contents(
                $this->getVM()->getCachePath($this->kernelRootDir).self::PUPPET_BASE_MANIFEST_FILE,
                $puppetBaseFileContent
            );
        }
    }

    /**
     * Generate archive from files.
     */
    private function generateArchiveFromFiles()
    {
        $vmArchive = new TarArchive($this->getVM()->getArchivePath($this->kernelRootDir, true));

        $vmArchive->add($this->getVM()->getCachePath($this->kernelRootDir));
    }
}
