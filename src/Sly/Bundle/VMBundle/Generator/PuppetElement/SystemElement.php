<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * System Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class SystemElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'system';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getGitModules()
    {
        return array(
            array('modules/puppi', 'https://github.com/example42/puppi.git'),
            array('modules/stdlib', 'https://github.com/puppetlabs/puppetlabs-stdlib.git'),
            array('modules/vcsrepo', 'https://github.com/puppetlabs/puppetlabs-vcsrepo.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:SystemElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }
}
