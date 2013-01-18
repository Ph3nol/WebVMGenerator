<?php

namespace Sly\Bundle\VMBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Sly\Bundle\VMBundle\DependencyInjection\Compiler\AddPuppetElementCompilerPass;

/**
 * SlyVMBundle.
 *
 * @uses Bundle
 * @author Cédric Dugat <cedric@dugat.me>
 */
class SlyVMBundle extends Bundle
{
    /**
     * Build.
     * 
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddPuppetElementCompilerPass());
    }
}
