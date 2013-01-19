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
    const GIT_MODULES_FILE          = '/.gitmodules';
    const VAGRANT_FILE              = '/Vagrantfile';
    const PUPPET_BASE_MANIFEST_FILE = '/manifests/base.pp';

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
        $vbName         = $this->getVM()->getVagrantBox();
        $vbUrl          = $vagrantBoxes[$this->getVM()->getVagrantBox()]['url'];
        $puppetBaseFile = explode('/', self::PUPPET_BASE_MANIFEST_FILE);
        $puppetBaseFile = end($puppetBaseFile);
        $vbHostname     = $this->getVM()->getHostname();
        $vbIpAddress    = $this->getVM()->getIp();
        $vbTimezone     = $this->getVM()->getTimezone();

        $vagrantFileContent = <<< EOF
Vagrant::Config.run do |config|
    config.vm.box     = "$vbName"
    config.vm.box_url = "$vbUrl"
    
    config.vm.customize [
        "modifyvm", :id,
        "--name", "$vbHostname"
    ]

    config.vm.network :hostonly, "$vbIpAddress"
    config.vm.host_name = "$vbHostname"

    config.vm.share_folder "vagrant", "/vagrant", "."
    config.vm.share_folder "project", "/project", ".."

    config.vm.provision :shell, :inline => "echo \"$vbTimezone\" | sudo tee /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata"

    config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "manifests"
        puppet.module_path    = "modules"
        puppet.manifest_file  = "$puppetBaseFile"
    end
end

EOF;

        file_put_contents(
            $this->getVM()->getCachePath($this->kernelRootDir).self::VAGRANT_FILE,
            $vagrantFileContent
        );
    }

    /**
     * Generate Puppet elements.
     */
    private function generatePuppetElements()
    {
        $gitSubmodulesContent  = array();
        $puppetBaseFileContent = array();

        foreach ($this->puppetElements as $puppetElement) {
            $puppetElement->setGenerator($this);

            if ($puppetElement->getCondition() && $puppetElement->getGitSubmodulesContent()) {
                $gitSubmodulesContent[] = $puppetElement->getGitSubmodulesContent();
            }

            if ($puppetElement->getCondition() && $puppetElement->getManifestContent()) {
                $puppetBaseFileContent[] = $puppetElement->getManifestContent();
            }

            $puppetElement->postProcess();
        }

        if ((bool) count($gitSubmodulesContent)) {
            $gitSubmodulesContent = implode('', $gitSubmodulesContent);

            file_put_contents(
                $this->getVM()->getCachePath($this->kernelRootDir).self::GIT_MODULES_FILE,
                $gitSubmodulesContent
            );
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
        $vmArchive = new TarArchive($this->getVM()->getArchivePath($this->kernelRootDir));

        $vmArchive->add($this->getVM()->getCachePath($this->kernelRootDir));
    }
}
