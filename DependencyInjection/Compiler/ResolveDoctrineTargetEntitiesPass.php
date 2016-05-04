<?php

namespace C2is\Bundle\SocialWallBundle\DependencyInjection\Compiler;

use C2is\Bundle\SocialWallBundle\DependencyInjection\DoctrineTargetEntitiesResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ResolveDoctrineTargetEntitiesPass
 *
 * @package C2is\Bundle\SocialWallBundle\DependencyInjection\Compiler
 */
class ResolveDoctrineTargetEntitiesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('c2is_social_wall.persistence_type_orm')) {
            $resolver = new DoctrineTargetEntitiesResolver();
            $resolver->resolve($container);
        }
    }
}
