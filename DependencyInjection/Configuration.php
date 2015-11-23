<?php

namespace C2is\Bundle\SocialWallBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link
 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('c2is_social_wall');

        $rootNode
            ->children()
                ->arrayNode('social_networks')
                    ->treatNullLike(array())
                    ->children()
                        ->arrayNode('facebook')
                            ->children()
                                ->scalarNode('user_id')
                                    ->info('Used to fetch posts and number of followers of a specific user')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->integerNode('limit')
                                    ->cannotBeEmpty()
                                    ->min(0)->max(500)
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                            ->append($this->addApiNode())
                        ->end()
                        ->arrayNode('twitter')
                            ->children()
                                ->arrayNode('tags')
                                    ->info('Used to fetch posts with the given tags')
                                ->end()
                                ->scalarNode('user_id')
                                    ->info('Used to fetch posts number of followers of a specific user')
                                ->end()
                                ->integerNode('limit')
                                    ->cannotBeEmpty()
                                    ->min(0)->max(500)
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                            ->append($this->addApiNode())
                        ->end()
                        ->arrayNode('flickr')
                            ->children()
                                ->arrayNode('tags')
                                    ->info('Used to fetch posts with the given tags')
                                ->end()
                                ->scalarNode('user_id')
                                    ->info('Used to fetch posts number of followers of a specific user')
                                ->end()
                                ->integerNode('limit')
                                    ->cannotBeEmpty()
                                    ->min(0)->max(500)
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                            ->append($this->addApiNode())
                        ->end()
                        ->arrayNode('instagram')
                            ->children()
                                ->scalarNode('tag')
                                    ->info('Used to fetch posts with the given tag')
                                ->end()
                                ->scalarNode('user_id')
                                    ->info('Used to fetch posts number of followers of a specific user')
                                ->end()
                                ->integerNode('limit')
                                    ->cannotBeEmpty()
                                    ->min(0)->max(500)
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                            ->append($this->addApiNode())
                        ->end()
                        ->arrayNode('google_plus')
                            ->children()
                                ->scalarNode('user_id')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->info('Used to fetch posts number of followers of a specific user')
                                ->end()
                                ->integerNode('limit')
                                    ->cannotBeEmpty()
                                    ->min(0)->max(50)
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                            ->append($this->addApiNode())
                        ->end()
                        ->arrayNode('youtube')
                            ->children()
                                ->scalarNode('channel_id')
                                    ->info('Used to fetch posts number of followers of a specific user')
                                ->end()
                                ->scalarNode('playlist_id')
                                    ->info('Used to fetch posts number of followers of a specific playlist')
                                ->end()
                                ->integerNode('limit')
                                    ->cannotBeEmpty()
                                    ->min(0)->max(50)
                                    ->defaultValue(50)
                                ->end()
                            ->end()
                            ->append($this->addApiNode())
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

    public function addApiNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('api');

        $node
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('api_secret')->end()
            ->end()
        ;

        return $node;
    }
}
