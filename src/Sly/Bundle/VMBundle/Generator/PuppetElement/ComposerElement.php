<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Composer Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ComposerElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'composer';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getComposer();
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/composer', 'https://github.com/willdurand/puppet-composer.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        $lines = <<< EOF
class { 'composer':
    command_name => 'composer',
    target_dir   => '/usr/local/bin',
    auto_update  => true
}
EOF;

        return $lines;
    }
}
