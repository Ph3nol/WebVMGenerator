<?php

namespace Sly\Bundle\VMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * AddPuppetElementCompilerPass.
 *
 * @uses CompilerPassInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class AddPuppetElementCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $puppetElements = $container->getDefinition('sly_vm.generator.puppet_element_collection');

        foreach ($container->findTaggedServiceIds('sly_vm.puppet_element') as $id => $tags) {
            $puppetElement = $container->getDefinition($id);

            $puppetElements->addMethodCall('add', array($puppetElement));
        }
    }
}
