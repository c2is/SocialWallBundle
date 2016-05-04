<?php

namespace C2is\Bundle\SocialWallBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Media
 *
 * @package C2is\Bundle\SocialWallBundle\Model
 */
class Media
{
    /** @var integer @Serializer\Type("integer") */
    protected $id;

    /** @var string @Serializer\Type("string") */
    protected $type;

    /** @var string @Serializer\Type("string") */
    protected $link;

    /** @var integer @Serializer\Type("integer") */
    protected $width;

    /** @var integer @Serializer\Type("integer") */
    protected $height;

    /** @var SocialItem @Serializer\Type("C2is\Bundle\SocialWallBundle\Model\SocialItem") */
    protected $item;

    /** @var SocialUser @Serializer\Type("C2is\Bundle\SocialWallBundle\Model\SocialUser") */
    protected $user;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return SocialItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param SocialItem $item
     *
     * @return $this
     */
    public function setItem(SocialItem $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return SocialUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param SocialUser $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
