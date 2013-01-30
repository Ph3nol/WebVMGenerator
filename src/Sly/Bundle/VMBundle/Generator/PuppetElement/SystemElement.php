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
        $modules = array(
            array('modules/puppi', 'https://github.com/example42/puppi.git'),
            array('modules/stdlib', 'https://github.com/puppetlabs/puppetlabs-stdlib.git'),
            array('modules/vcsrepo', 'https://github.com/puppetlabs/puppetlabs-vcsrepo.git'),
            array('modules/apt', 'https://github.com/puppetlabs/puppetlabs-apt.git'),
        );

        if (in_array('nodejs', $this->getVM()->getSystemPackages())) {
            $modules[] = array(
                'modules/nodejs', 'https://github.com/willdurand/puppet-nodejs.git',
            );
        }

        return $modules;
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $systemPackages = $this->getVM()->getSystemPackages();

        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:SystemElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }
}
