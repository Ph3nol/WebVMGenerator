<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Vim Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VimElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'vim';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getVim();
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return 'system::package { "vim": }';
    }
}
