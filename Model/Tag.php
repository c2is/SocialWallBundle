<?php

namespace C2is\Bundle\SocialWallBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Tag
 *
 * @package C2is\Bundle\SocialWallBundle\Model
 */
class Tag
{
    /** @var integer @Serializer\Type("integer") */
    protected $id;

    /** @var string @Serializer\Type("string") */
    protected $name;

    /** @var string @Serializer\Type("string") */
    protected $link;

    /** @var ArrayCollection @Serializer\Type("ArrayCollection<C2is\Bundle\SocialWallBundle\Model\SocialItem>") @Serializer\Exclude */
    protected $items;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param SocialItem $item
     *
     * @return $this
     */
    public function addItem($item)
    {
        $this->items->add($item);

        return $this;
    }

    /**
     * @param SocialItem $item
     *
     * @return $this
     */
    public function removeItem($item)
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @param ArrayCollection $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }
}
