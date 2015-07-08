<?php

namespace C2is\Bundle\SocialWallBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('c2is_social_wall');

        $rootNode
            ->children()
                ->arrayNode('social_networks')
                    ->treatNullLike(array())
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('api_key')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('limit')
                                ->cannotBeEmpty()
                                ->defaultValue('50')
                            ->end()
                            ->scalarNode('query')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('api_secret')->end()
                            ->scalarNode('query')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('duration')
                        ->cannotBeEmpty()
                        ->defaultValue('3600')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
