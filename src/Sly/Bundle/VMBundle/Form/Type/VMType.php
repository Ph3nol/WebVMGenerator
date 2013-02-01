<?php

namespace Sly\Bundle\VMBundle\Form\Type;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Entity\VM
;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\HttpFoundation\Request
;

/**
 * VM form type.
 *
 * @uses \Symfony\Component\Form\AbstractType
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VMType extends AbstractType
{
    /**
     * @var \Sly\Bundle\VMBundle\Entity\VM
     */
    private $defaultVM;

    /**
     * @var array
     */
    private $bundleConfig;

    /**
     * Constructor.
     *
     * @param \Sly\Bundle\VMBundle\Entity\VM $defaultVM    Default VM entity
     * @param array                          $bundleConfig Bundle container configuration
     */
    public function __construct(VM $defaultVM, array $bundleConfig)
    {
        $this->defaultVM    = $defaultVM;
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultConfig  = Config::getVMDefaultConfig();
        $configurations = array();

        $configurations[Config::DEFAULT_CONFIG_NAME] = $defaultConfig['label'];

        foreach ($this->bundleConfig['configurations'] as $configKey => $config) {
            $configurations[$configKey] = $config['label'];
        }

        $vagrantBoxes        = Config::getVagrantBoxes();
        $vagrantBoxesChoices = array();

        foreach ($vagrantBoxes as $vagrantKey => $vagrantBox) {
            $vagrantBoxesChoices[$vagrantKey] = $vagrantBox['name'];
        }

        $phpVersions = array('5.3', '5.4');

        $phpModules = array(
            'php5-mysql', 'php5-sqlite', 'php5-intl', 'php5-cli', 'php5-imagick', 'php5-gd', 'php5-xsl',
            'php5-mcrypt', 'php5-curl', 'php5-xdebug', 'php5-imap', 'php5-apc',
        );

        $phpPearComponents = array(
            'phpunit'        => 'PHPUnit',
            'phpcodesniffer' => 'PHPCodeSniffer',
        );

        $systemPackages = array(
            'admin'           => 'Admin monitoring tools',
            'build-essential' => 'Build-Essential package',
            'curl'            => 'Curl',
            'git-core'        => 'Git',
            'vim'             => 'Vim',
            'sendmail'        => 'Sendmail',
            'lynx'            => 'Lynx',
            'nodejs'          => 'Node.js',
            'yui-compressor'  => 'Java environment + YUI',
        );

        $rubyPackages = array(
            'mailcatcher' => 'MailCatcher',
            'capistrano'  => 'Capistrano',
            'capifony'    => 'Capifony',
        );

        $builder
            ->add('configuration', 'choice', array(
                'choices'  => $configurations,
                'data'     => Config::DEFAULT_CONFIG_NAME,
                'multiple' => false,
                'required' => true,
            ))
            ->add('vagrantBox', 'choice', array(
                'choices'  => $vagrantBoxesChoices,
                'data'     => $this->defaultVM->getVagrantBox(),
                'multiple' => false,
                'required' => true,
            ))
            ->add('vagrantNFS', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getVagrantNFS()
            ))
            ->add('vagrantMemory', 'text', array(
                'required' => true,
                'attr'     => array('placeholder' => $this->defaultVM->getVagrantMemory())
            ))
            ->add('vagrantCpu', 'choice', array(
                'choices'  => array_combine(range(1,8), range(1,8)),
                'data'     => $this->defaultVM->getVagrantCpu(),
                'multiple' => false,
                'required' => true,
            ))
            ->add('vagrantFinalLaunch', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getVagrantFinalLaunch(),
            ))
            ->add('name', 'text', array(
                'required' => true,
                'attr'     => array('placeholder' => (string) $this->defaultVM)
            ))
            ->add('ip', 'text', array(
                'required' => true,
                'attr'     => array('placeholder' => $this->defaultVM->getIp())
            ))
            ->add('hostname', 'text', array(
                'required' => true,
                'attr'     => array('placeholder' => $this->defaultVM->getHostname())
            ))
            ->add('timezone', 'timezone', array(
                'required' => true,
                'data'     => $this->defaultVM->getTimezone()
            ))
            ->add('apache', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getApache()
            ))
            ->add('apachePort', 'text', array(
                'required' => false,
                'data'     => $this->defaultVM->getApachePort()
            ))
            ->add('apacheRootDir', 'text', array(
                'required' => true,
                'data'     => $this->defaultVM->getApacheRootDir()
            ))
            ->add('apacheSSL', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getApacheSSL()
            ))
            ->add('nginx', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getNginx()
            ))
            ->add('varnish', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getVarnish()
            ))
            ->add('mysql', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getMysql()
            ))
            ->add('mysqlRootPassword', 'text', array(
                'required' => false,
                'data'     => $this->defaultVM->getMysqlRootPassword()
            ))
            ->add('php', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getPhp()
            ))
            ->add('phpXDebugMaxNestingLevel', 'text', array(
                'required' => true,
                'data'     => $this->defaultVM->getPhpXDebugMaxNestingLevel()
            ))
            ->add('phpVersion', 'choice', array(
                'choices'  => array_combine($phpVersions, $phpVersions),
                'data'     => $this->defaultVM->getPhpVersion(),
                'multiple' => false,
                'required' => true,
            ))
            ->add('phpPearComponents', 'choice', array(
                'choices'  => $phpPearComponents,
                'data'     => $this->defaultVM->getPhpPearComponents(),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('phpMyAdmin', 'checkbox', array(
                'required' => false,
                'data'     => $this->defaultVM->getPhpMyAdmin()
            ))
            ->add('phpModules', 'choice', array(
                'choices'  => array_combine($phpModules, $phpModules),
                'data'     => $this->defaultVM->getPhpModules(),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('systemPackages', 'choice', array(
                'choices'  => $systemPackages,
                'data'     => $this->defaultVM->getSystemPackages(),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('rubyPackages', 'choice', array(
                'choices'  => $rubyPackages,
                'data'     => $this->defaultVM->getRubyPackages(),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('vimConfig', 'checkbox', array(
                'required' => false,
                'data' => $this->defaultVM->getVimConfig()
            ))
            ->add('composer', 'checkbox', array(
                'required' => false,
                'data' => $this->defaultVM->getComposer()
            ))
            ->add('ohMyZsh', 'checkbox', array(
                'required' => false,
                'data' => $this->defaultVM->getOhMyZsh()
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultOptions = array(
            'data_class' => 'Sly\Bundle\VMBundle\Entity\VM',
        );

        $resolver->setDefaults($defaultOptions);
        $resolver->addAllowedValues(array());
    }

    /**
     * Set default options data from Request.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     */
    public function setDefaultOptionsDataFromRequest(Request $request)
    {
        /**
         * @todo $this->defaultVM = ...
         */
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sly_vm_form_type_vm';
    }
}
