<?php

namespace Sly\Bundle\VMBundle\Form\Type;

use Sly\Bundle\VMBundle\Entity\VM;

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
        $phpModules = array('cli', 'gd', 'posix', 'intl');

        $builder
            ->add('name', 'text', array('required' => true, 'attr' => array('placeholder' => (string) $this->defaultVM)))
            ->add('ip', 'text', array('required' => true, 'attr' => array('placeholder' => $this->defaultVM->getIp())))
            ->add('hostname', 'text', array('required' => true, 'attr' => array('placeholder' => $this->defaultVM->getHostname())))
            ->add('timezone', 'timezone', array('required' => true, 'data' => $this->defaultVM->getTimezone()))
            ->add('apache', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getApache()))
            ->add('apacheSSL', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getApacheSSL()))
            ->add('nginx', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getNginx()))
            ->add('varnish', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getVarnish()))
            ->add('mysql', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getMysql()))
            ->add('mysqlRootPassword', 'text', array('required' => false, 'data' => $this->defaultVM->getMysqlRootPassword()))
            ->add('phpModules', 'choice', array(
                'choices'  => array_combine($phpModules, $phpModules),
                'data'     => $phpModules,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('vim', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getVim()))
            ->add('vimConfig', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getVimConfig()))
            ->add('git', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getGit()))
            ->add('composer', 'checkbox', array('required' => false, 'data' => $this->defaultVM->getComposer()))
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
