<?php

namespace C2is\Bundle\SocialWallBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class ORMMetadataSubscriber
 *
 * @package C2is\Bundle\SocialWallBundle\EventListener
 */
class ORMMetadataSubscriber implements EventSubscriber
{
    /** @var array */
    protected $modelConfiguration = array();

    /**
     * @param array $modelConfiguration
     */
    public function __construct($modelConfiguration)
    {
        $this->modelConfiguration = $modelConfiguration;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'loadClassMetadata',
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        foreach ($this->modelConfiguration as $name => $configuration) {
            if ($metadata->getName() !== $configuration['class']) {
                continue;
            }

            if (isset($configuration['repository'])) {
                $metadata->setCustomRepositoryClass($configuration['repository']);
            }

            $metadata->isMappedSuperclass = false;
        }

        if (!$metadata->isMappedSuperclass) {
            $this->setAssociationMappings($metadata, $eventArgs->getEntityManager()->getConfiguration());
        } else {
            $this->unsetAssociationMappings($metadata);
        }
    }

    /**
     * @param ClassMetadataInfo $metadata
     * @param Configuration $configuration
     */
    protected function setAssociationMappings(ClassMetadataInfo $metadata, Configuration $configuration)
    {
        foreach (class_parents($metadata->getName()) as $parent) {
            $parentMetadata = new ClassMetadata(
                $parent,
                $configuration->getNamingStrategy()
            );

            if (in_array($parent, $configuration->getMetadataDriverImpl()->getAllClassNames())) {
                $configuration->getMetadataDriverImpl()->loadMetadataForClass($parent, $parentMetadata);
                if ($parentMetadata->isMappedSuperclass) {
                    foreach ($parentMetadata->getAssociationMappings() as $key => $value) {
                        if ($this->isRelation($value['type'])) {
                            $metadata->associationMappings[$key] = $value;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param ClassMetadataInfo $metadata
     */
    protected function unsetAssociationMappings(ClassMetadataInfo $metadata)
    {
        foreach ($metadata->getAssociationMappings() as $key => $value) {
            if ($this->isRelation($value['type'])) {
                unset($metadata->associationMappings[$key]);
            }
        }
    }

    /**
     * @param $type
     *
     * @return bool
     */
    protected function isRelation($type)
    {
        return in_array(
            $type,
            array(
                ClassMetadataInfo::MANY_TO_MANY,
                ClassMetadataInfo::ONE_TO_MANY,
                ClassMetadataInfo::ONE_TO_ONE,
            ),
            true
        );
    }
}
