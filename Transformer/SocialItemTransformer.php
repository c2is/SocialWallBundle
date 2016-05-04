<?php

namespace C2is\Bundle\SocialWallBundle\Transformer;

use C2is\Bundle\SocialWallBundle\Model\Media;
use C2is\Bundle\SocialWallBundle\Model\SocialItem;
use C2is\Bundle\SocialWallBundle\Model\SocialUser;
use C2is\Bundle\SocialWallBundle\Model\Tag;
use C2iS\SocialWall\Instagram\Model\Media as InstagramMedia;
use C2iS\SocialWall\Instagram\Model\SocialItem as InstagramSocialItem;
use C2iS\SocialWall\Model\AbstractSocialItem;
use C2iS\SocialWall\Model\SocialUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class SocialItemTransformer
 *
 * @package C2is\Bundle\SocialWallBundle\Mapper
 */
class SocialItemTransformer
{
    /**
     * @param \C2iS\SocialWall\Model\AbstractSocialItem      $source
     * @param \C2is\Bundle\SocialWallBundle\Model\SocialItem $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\SocialItem
     */
    public function transform(AbstractSocialItem $source, SocialItem $dest = null)
    {
        if (null === $dest) {
            $dest = new SocialItem();
        }

        if (null === $dest->getMedias()) {
            $dest->setMedias(new ArrayCollection());
        }

        if (null === $dest->getTags()) {
            $dest->setTags(new ArrayCollection());
        }

        $dest->setSocialId($source->getId());
        $dest->setPublishedAt($source->getDatetime());
        $dest->setLikes($source->getFollowers());
        $dest->setLink($source->getLink());
        $dest->setTitle($source->getTitle());
        $dest->setType($source->getSocialItemType());

        if ($source instanceof InstagramSocialItem) {
            $dest->setLatitude($source->getLatitude());
            $dest->setLongitude($source->getLongitude());
            $dest->setLikes($source->getLikes());
            $dest->setComments($source->getComments());

            foreach ($source->getImages() as $image) {
                $dest->addMedia($this->transformInstagramMedia($image));
            }

            foreach ($source->getVideos() as $video) {
                $dest->addMedia($this->transformInstagramMedia($video));
            }
        }

        return $dest;
    }

    /**
     * @param SocialUserInterface                            $source
     * @param \C2is\Bundle\SocialWallBundle\Model\SocialUser $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\SocialUser
     */
    public function transformUser(SocialUserInterface $source, SocialUser $dest = null)
    {
        if (null === $dest) {
            $dest = new SocialUser();
        }

        $dest->setSocialId($source->getId());
        $dest->setLink($source->getUrl());
        $dest->setName($source->getName());

        return $dest;
    }

    /**
     * @param InstagramMedia                            $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Media $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Media
     */
    public function transformInstagramMedia(InstagramMedia $source, Media $dest = null)
    {
        if (null === $dest) {
            $dest = new Media();
        }

        $dest->setLink($source->getUrl());
        $dest->setHeight($source->getHeight());
        $dest->setWidth($source->getWidth());
        $dest->setType($source->getType());

        return $dest;
    }

    /**
     * @param string                                  $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Tag $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Tag
     */
    public function transformInstagramTag($source, Tag $dest = null)
    {
        if (null === $dest) {
            $dest = new Tag();
        }

        $dest->setName($source);

        return $dest;
    }
}
