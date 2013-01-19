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
        
        $treeBuilder
            ->root('sly_vm')
            ->children()
                ->arrayNode('configurations')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('configName')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('vagrantBox')->end()
                            ->scalarNode('name')->end()
                            ->scalarNode('hostname')->end()
                            ->scalarNode('ip')->end()
                            ->scalarNode('timezone')->end()
                            ->arrayNode('systemPackages')->end()
                            ->scalarNode('apache')->end()
                            ->scalarNode('apacheSSL')->end()
                            ->scalarNode('nginx')->end()
                            ->scalarNode('varnish')->end()
                            ->booleanNode('mysql')->end()
                            ->scalarNode('mysqlRootPassword')->end()
                            ->booleanNode('php')->end()
                            ->scalarNode('phpVersion')->end()
                            ->arrayNode('phpModules')->end()
                            ->arrayNode('phpPearComponents')->end()
                            ->booleanNode('vimConfig')->end()
                            ->booleanNode('composer')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
