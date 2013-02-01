<?php

namespace Sly\Bundle\VMBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\TwigBundle\TwigEngine;
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
    const README_FILE               = '/README';
    const VAGRANT_SKELETON_PATH     = '/Resources/skeleton/vagrant';
    const VAGRANT_FILE              = '/Vagrantfile';
    const PUPPET_BASE_MANIFEST_DIR  = '/manifests';
    const PUPPET_BASE_MANIFEST_FILE = '/manifests/base.pp';
    const VAGRANT_CONFIG_INSTALL    = 'install_%s.sh';

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    private $templating;

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
     * @var array
     */
    private $vmInstallScriptElements = array();

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\Filesystem\Filesystem                             $filesystem     Filesystem
     * @param \Symfony\Bundle\TwigBundle\TwigEngine                                $templating     Templating service
     * @param \Sly\Bundle\VMBundle\Config\Config                                   $config         Config
     * @param \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementCollection $puppetElements Puppet elements collection
     * @param string                                                               $kernelRootDir  Kernel root directory
     */
    public function __construct(Filesystem $filesystem, TwigEngine $templating, Config $config, PuppetElementCollection $puppetElements, $kernelRootDir)
    {
        $this->filesystem     = $filesystem;
        $this->templating     = $templating;
        $this->config         = $config;
        $this->puppetElements = $puppetElements;
        $this->kernelRootDir  = $kernelRootDir;

        if (false === is_dir($this->kernelRootDir.'/cache/vm')) {
            $this->filesystem->mkdir($this->kernelRootDir.'/cache/vm', 0777);
        }
    }

    /**
     * Get KernelRootDir value.
     *
     * @return string KernelRootDir value to get
     */
    public function getKernelRootDir()
    {
        return $this->kernelRootDir;
    }

    /**
     * Get Templating service.
     *
     * @return \Symfony\Bundle\TwigBundle\TwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
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
     * Get Filesystem value.
     *
     * @return \Symfony\Component\Filesystem\Filesystem Filesystem value to get
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Get PuppetElements value.
     *
     * @return \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementCollection
     */
    public function getPuppetElements()
    {
        return $this->puppetElements;
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

        if (file_exists($vm->getArchivePath($this->kernelRootDir))) {
            $this->filesystem->remove($vm->getArchivePath($this->kernelRootDir));
        }

        $this->filesystem->mirror(
            __DIR__.'/..'.self::VAGRANT_SKELETON_PATH,
            $this->getVM()->getCachePath($this->kernelRootDir),
            null,
            array('override' => true)
        );

        $this->generateVagrantFile();
        $this->generateReadmeFile();
        $this->generatePuppetElements();
        $this->generateArchiveFromFiles();

        $this->filesystem->remove($this->getVM()->getCachePath($this->kernelRootDir));

        return $this->getVM();
    }

    /**
     * Generate Vagrant file.
     */
    private function generateVagrantFile()
    {
        $vagrantBoxes   = Config::getVagrantBoxes();
        $vbUrl          = $vagrantBoxes[$this->getVM()->getVagrantBox()]['url'];
        $puppetBaseFile = explode('/', self::PUPPET_BASE_MANIFEST_FILE);
        $puppetBaseFile = end($puppetBaseFile);

        $vagrantFileContent = $this->getTemplating()->render('SlyVMBundle:VM:Vagrantfile.html.twig', array(
            'puppetBaseFile' => $puppetBaseFile,
            'vm'             => $this->getVM(),
            'vbUrl'          => $vagrantBoxes[$this->getVM()->getVagrantBox()]['url'],
        ));

        file_put_contents(
            $this->getVM()->getCachePath($this->kernelRootDir).self::VAGRANT_FILE,
            $vagrantFileContent
        );
    }

    /**
     * Generate README file.
     */
    private function generateReadmeFile()
    {
        $readmeFileContent = $this->getTemplating()->render('SlyVMBundle:VM:readme.html.twig', array(
            'vm' => $this->getVM(),
        ));

        file_put_contents(
            $this->getVM()->getCachePath($this->kernelRootDir).self::README_FILE,
            $readmeFileContent
        );
    }

    /**
     * Generate Puppet elements.
     *
     * @return array
     */
    private function generatePuppetElements()
    {
        $puppetBaseFileContent = array();

        foreach ($this->puppetElements as $puppetElement) {
            $puppetElement->setGenerator($this);

            if ($puppetElement->getCondition() && $puppetElement->getManifestContent()) {
                $puppetBaseFileContent[] = $puppetElement->getManifestContent();
            }

            $puppetElement->postProcess();
        }

        if ((bool) count($puppetBaseFileContent)) {
            $this->filesystem->mkdir(
                $this->getVM()->getCachePath($this->kernelRootDir).self::PUPPET_BASE_MANIFEST_DIR,
                0777
            );

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
        $vmArchive = new TarArchive($this->getVM()->getArchivePath($this->kernelRootDir));

        $vmArchive->add($this->getVM()->getCachePath($this->kernelRootDir));
    }
}
