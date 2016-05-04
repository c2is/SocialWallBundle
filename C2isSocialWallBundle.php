<?php

namespace C2is\Bundle\SocialWallBundle;

use C2is\Bundle\SocialWallBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntitiesPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class C2isSocialWallBundle
 *
 * @package C2is\Bundle\SocialWallBundle
 */
class C2isSocialWallBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $modelDir = realpath(__DIR__.'/Resources/config/doctrine/model');
        $mappings = array(
            $modelDir => 'C2is\Bundle\SocialWallBundle\Model',
        );

        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';
        if (class_exists($ormCompilerClass)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createYamlMappingDriver(
                    $mappings,
                    array('c2is_social_wall.model_manager_name'),
                    'c2is_social_wall.persistence_type_orm',
                    array('C2isSocialWallBundle' => 'C2is\Bundle\SocialWallBundle\Model')
                ));
        }

        $container->addCompilerPass(new ResolveDoctrineTargetEntitiesPass());
    }
}
