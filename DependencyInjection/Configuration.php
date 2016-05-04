<?php

namespace C2is\Bundle\SocialWallBundle\DependencyInjection;

use C2is\Bundle\SocialWallBundle\Model\SocialItem;
use C2is\Bundle\SocialWallBundle\Model\SocialUser;
use C2is\Bundle\SocialWallBundle\Model\Media;
use C2is\Bundle\SocialWallBundle\Model\Tag;
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
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->validate()
                        ->ifTrue(function ($v) { return count(array_filter($v, function ($persistence) { return $persistence['enabled']; })) > 1; })
                        ->thenInvalid('Only one persistence layer can be enabled at the same time.')
                    ->end()
                    ->children()
                        ->arrayNode('orm')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('manager_name')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->addClassNode('social_item', SocialItem::class))
                        ->append($this->addClassNode('social_user', SocialUser::class))
                        ->append($this->addClassNode('social_media', Media::class))
                        ->append($this->addClassNode('social_tag', Tag::class))
                    ->end()
                ->end()
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
                                ->scalarNode('user')
                                    ->info('Used to fetch tweets from a specific user')
                                ->end()
                                ->scalarNode('user_id')
                                    ->info('Used to fetch number of tweets and number of followers of a specific user')
                                ->end()
                                ->integerNode('limit')
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

    public function addClassNode($name, $class)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($name);

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('options')->end()
                ->scalarNode('class')->defaultValue($class)->cannotBeEmpty()->end()
                ->scalarNode('default_class')->defaultValue($class)->cannotBeEmpty()->cannotBeOverwritten()->end()
            ->end()
        ;

        return $node;
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
                ->scalarNode('redirect_uri')->end()
            ->end()
        ;

        return $node;
    }
}
