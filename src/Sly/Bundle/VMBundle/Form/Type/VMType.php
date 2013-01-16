<?php

namespace Sly\Bundle\VMBundle\Form\Type;

use Sly\Bundle\VMBundle\Entity\VM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('name', 'text', array('required' => true))
            ->add('ip', 'text', array('required' => true))
            ->add('hostname', 'text', array('required' => true))
            ->add('timezone', 'timezone', array('required' => true))
            ->add('apache', 'checkbox', array('required' => false))
            ->add('apacheSSL', 'checkbox', array('required' => false))
            ->add('nginx', 'checkbox', array('required' => false))
            ->add('varnish', 'checkbox', array('required' => false))
            ->add('mysql', 'checkbox', array('required' => false))
            ->add('mysqlRootPassword', 'text', array('required' => false))
            ->add('phpModules', 'choice', array(
                'choices'  => array_combine($phpModules, $phpModules),
                'data'     => $phpModules,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('vim', 'checkbox', array('required' => false))
            ->add('vimConfig', 'checkbox', array('required' => false))
            ->add('git', 'checkbox', array('required' => false))
            ->add('composer', 'checkbox', array('required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultOptions = array(
            'data_class' => 'Sly\Bundle\VMBundle\Entity\VM',
            'data' => $this->defaultVM,
        );

        $resolver->setDefaults($defaultOptions);
        $resolver->addAllowedValues(array());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sly_vm_form_type_vm';
    }
}
