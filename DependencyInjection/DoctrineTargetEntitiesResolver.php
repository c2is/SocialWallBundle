<?php

namespace C2is\Bundle\SocialWallBundle\DependencyInjection;

use Doctrine\ORM\Version;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DoctrineTargetEntitiesResolver
 *
 * @package C2is\Bundle\SocialWallBundle\DependencyInjection
 */
class DoctrineTargetEntitiesResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('doctrine.orm.listeners.resolve_target_entity')) {
            throw new \RuntimeException('Cannot find Doctrine Target Entity Resolver Listener.');
        }

        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        foreach ($container->getParameter('c2is_social_wall.model_configuration') as $name => $config) {
            if (($class = $config['class']) !== ($defaultClass = $config['default_class'])) {
                $resolveTargetEntityListener
                    ->addMethodCall('addResolveTargetEntity', array(
                        $defaultClass,
                        $class,
                        array(),
                    ))
                ;
            }
        }

        if (!($resolveTargetEntityListener->hasTag('doctrine.event_listener') || $resolveTargetEntityListener->hasTag('doctrine.event_subscriber'))) {
            if (version_compare(Version::VERSION, '2.5.0-DEV') < 0) {
                $resolveTargetEntityListener->addTag('doctrine.event_listener', array('event' => 'loadClassMetadata'));
            } else {
                var_dump('CA DOIT PASSER ICI SA MERE');
                $resolveTargetEntityListener->addTag('doctrine.event_subscriber');
            }
        }
    }
}
