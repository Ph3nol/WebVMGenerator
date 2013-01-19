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
     * Constructor.
     *
     * @param \Sly\Bundle\VMBundle\Entity\VM $defaultVM Default VM entity
     */
    public function __construct(VM $defaultVM)
    {
        $this->defaultVM = $defaultVM;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $vagrantBoxes        = Config::getVagrantBoxes();
        $vagrantBoxesChoices = array();

        foreach ($vagrantBoxes as $vagrantKey => $vagrantBox) {
            $vagrantBoxesChoices[$vagrantKey] = $vagrantBox['name'];
        }

        $phpVersions = array('5.3', '5.4');

        $phpModules = array(
            'mysql', 'intl', 'xdebug', 'curl', 'sqlite',
            'imagick', 'suhosin', 'apc'
        );

        $phpPearComponents = array(
            'phpunit'        => 'PHPUnit',
            'phpcodesniffer' => 'PHPCodeSniffer',
        );

        $systemPackages = array(
            'git-core' => 'Git',
            'vim'      => 'Vim',
            'sendmail' => 'Sendmail',
            'lynx'     => 'Lynx'
        );

        $builder
            ->add('vagrantBox', 'choice', array(
                'choices'  => $vagrantBoxesChoices,
                'data'     => $this->defaultVM->getVagrantBox(),
                'multiple' => false,
                'required' => true,
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
            ->add('vimConfig', 'checkbox', array(
                'required' => false,
                'data' => $this->defaultVM->getVimConfig()
            ))
            ->add('composer', 'checkbox', array(
                'required' => false,
                'data' => $this->defaultVM->getComposer()
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
