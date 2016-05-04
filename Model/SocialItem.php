<?php

namespace C2is\Bundle\SocialWallBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class SocialItem
 *
 * @package C2is\Bundle\SocialWallBundle\Model
 */
class SocialItem
{
    /** @var integer @Serializer\Type("integer") */
    protected $id;

    /** @var string @Serializer\Type("string") */
    protected $socialId;

    /** @var string @Serializer\Type("string") */
    protected $type;

    /** @var string @Serializer\Type("string") */
    protected $title;

    /** @var string @Serializer\Type("string") */
    protected $link;

    /** @var double @Serializer\Type("double") */
    protected $latitude;

    /** @var double @Serializer\Type("double") */
    protected $longitude;

    /** @var ArrayCollection @Serializer\Type("ArrayCollection<C2is\Bundle\SocialWallBundle\Model\Tag>") */
    protected $tags;

    /** @var ArrayCollection @Serializer\Type("ArrayCollection<C2is\Bundle\SocialWallBundle\Model\Media>") */
    protected $medias;

    /** @var integer @Serializer\Type("integer") */
    protected $likes;

    /** @var integer @Serializer\Type("integer") */
    protected $comments;

    /** @var \DateTime @Serializer\Type("DateTime") */
    protected $publishedAt;

    /** @var SocialUser @Serializer\Type("C2is\Bundle\SocialWallBundle\Model\SocialUser") */
    protected $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->medias = new ArrayCollection();
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
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * @param string $socialId
     *
     * @return $this
     */
    public function setSocialId($socialId)
    {
        $this->socialId = $socialId;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addItem($this);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeItem($this);
        }

        return $this;
    }

    /**
     * @param ArrayCollection $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        if ($this->tags) {
            /** @var Tag $tag */
            foreach ($this->tags as $tag) {
                $tag->removeItem($this);
            }
        }

        $this->tags = $tags;

        /** @var Tag $tag */
        foreach ($this->tags as $tag) {
            $tag->addItem($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param Media $media
     *
     * @return $this
     */
    public function addMedia(Media $media)
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setItem($this);
        }

        return $this;
    }

    /**
     * @param Media $media
     *
     * @return $this
     */
    public function removeMedia(Media $media)
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
            $media->setItem(null);
        }

        return $this;
    }

    /**
     * @param ArrayCollection $medias
     *
     * @return $this
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        $images = array();

        foreach ($this->medias as $media) {
            if ('image' == $media->getType()) {
                $images[] = $media;
            }
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getVideos()
    {
        $videos = array();

        foreach ($this->medias as $media) {
            if ('video' == $media->getType()) {
                $videos[] = $media;
            }
        }

        return $videos;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     *
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return int
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param int $comments
     *
     * @return $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTime $publishedAt
     *
     * @return $this
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

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
