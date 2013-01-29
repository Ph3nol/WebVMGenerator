<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Ruby Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class RubyElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ruby';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) count($this->getVM()->getRubyPackages());
    }

    /**
     * {@inheritDoc}
     */
    public function getGitModules()
    {
        return array(
            array('modules/ruby', 'https://github.com/puppetlabs/puppetlabs-ruby.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:RubyElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }
}
