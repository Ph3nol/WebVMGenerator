<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * VimConfig Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VimConfigElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'vimConfig';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) (
            in_array('vim', $this->getVM()->getSystemPackages())
            && $this->getVM()->getVimConfig()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:VimConfigElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }
}
