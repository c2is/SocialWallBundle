<?php

namespace C2is\Bundle\SocialWallBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class C2isSocialWallExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $container->setParameter('c2is_social_wall', $config);
        $container->setParameter('c2is_social_wall.cache.duration', $config['cache']['duration']);

        $container->setParameter('c2is_social_wall.model_configuration', $config['model']);
        $container->setParameter('c2is_social_wall.persistence_type_orm', false);

        if ($config['persistence']['orm']['enabled']) {
            $container->setParameter('c2is_social_wall.persistence_type_orm', true);
            $container->setParameter('c2is_social_wall.persistence.orm.manager_name', $config['persistence']['orm']['manager_name']);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
