<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Example Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ExampleElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'example';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/example', 'https://github.com/example/puppet-example.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
    }
}
