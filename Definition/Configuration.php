<?php
/**
 * @author Vadym Pylypenko<vpylypenko@corevalue.net>
 */

namespace Demo\Definition;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('standalone_forms');

        $rootNode
            // templating
            ->children()
                ->arrayNode('templating')
                ->info('templating configuration')
                    ->children()
                        ->arrayNode('form_themes')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('template_path_patterns')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            // translator
            ->children()
                ->arrayNode('translator')
                ->info('translator configuration')
                    ->children()
                        ->scalarNode('default_locale')->defaultValue('en')->end()
                        ->arrayNode('resources')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('loaders')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}