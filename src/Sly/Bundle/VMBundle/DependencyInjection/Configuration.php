<?php

namespace Sly\Bundle\VMBundle\DependencyInjection;

use Sly\Bundle\VMBundle\Config\Config;

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
        $vmDefaults  = Config::getVMDefaults();
        
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
                                    ->scalarNode('name')->defaultValue($vmDefaults['configuration']['name'])->end()
                                    ->scalarNode('hostname')->defaultValue($vmDefaults['configuration']['hostname'])->end()
                                    ->scalarNode('ip')->defaultValue($vmDefaults['configuration']['ip'])->end()
                                    ->scalarNode('timezone')->defaultValue($vmDefaults['configuration']['timezone'])->end()
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
                            ->arrayNode('phpModules')->end()
                            ->arrayNode('tools')
                                ->children()
                                    ->booleanNode('git')->defaultValue(true)->end()
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
