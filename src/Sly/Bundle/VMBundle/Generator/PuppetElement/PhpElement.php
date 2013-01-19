<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * PHP Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PhpElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'php';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) count($this->getVM()->getPhp());
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/php', 'https://github.com/example42/puppet-php.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
    }
}
