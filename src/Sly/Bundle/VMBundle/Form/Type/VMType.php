<?php

namespace Sly\Bundle\VMBundle\Form\Type;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Config\VMCollection
;

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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $phpModules = array('cli', 'gd', 'posix', 'intl');

        $builder
            ->add('name', 'text')
            ->add('ip', 'text')
            ->add('hostname', 'text')
            ->add('timezone', 'timezone')
            ->add('apache', 'checkbox')
            ->add('apacheSSL', 'checkbox')
            ->add('nginx', 'checkbox')
            ->add('varnish', 'checkbox')
            ->add('mysql', 'checkbox')
            ->add('mysqlRootPassword', 'text')
            ->add('phpModules', 'choice', array(
                'choices'  => array_combine($phpModules, $phpModules),
                'data'     => $phpModules,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('vim', 'checkbox')
            ->add('vimConfig', 'checkbox')
            ->add('git', 'checkbox')
            ->add('composer', 'checkbox')
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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sly_vm_form_type_vm';
    }
}
