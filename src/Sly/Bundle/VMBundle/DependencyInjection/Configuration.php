<?php

namespace Sly\Bundle\VMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        
        $treeBuilder
            ->root('sly_vm')
            ->children()
                ->arrayNode('configurations')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('configuration')
                                ->children()
                                    ->scalarNode('name')->defaultValue('My VM, generated with WebVMGenerator')->end()
                                    ->scalarNode('hostname')->defaultValue('dev.local')->end()
                                    ->scalarNode('ip')->defaultValue('11.11.11.11')->end()
                                ->end()
                            ->end()
                            ->arrayNode('web')
                                ->children()
                                    ->scalarNode('apache')->defaultValue(true)->end()
                                    ->scalarNode('apacheSSL')->defaultValue(false)->end()
                                    ->scalarNode('nginx')->defaultValue(false)->end()
                                    ->scalarNode('varnish')->defaultValue(false)->end()
                                ->end()
                            ->end()
                            ->arrayNode('phpModules')
                                ->children()
                                    ->booleanNode('cli')->defaultValue(true)->end()
                                    ->booleanNode('posix')->defaultValue(true)->end()
                                    ->booleanNode('gd')->defaultValue(true)->end()
                                    ->booleanNode('intl')->defaultValue(true)->end()
                                ->end()
                            ->end()
                            ->arrayNode('tools')
                                ->children()
                                    ->booleanNode('vim')->defaultValue(true)->end()
                                    ->booleanNode('vimConfig')->defaultValue(false)->end()
                                    ->booleanNode('composer')->defaultValue(false)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
