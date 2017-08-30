<?php

namespace C2is\Bundle\SocialWallBundle\Transformer;

use C2is\Bundle\SocialWallBundle\Model\Media;
use C2is\Bundle\SocialWallBundle\Model\SocialItem;
use C2is\Bundle\SocialWallBundle\Model\SocialUser;
use C2is\Bundle\SocialWallBundle\Model\Tag;
use C2iS\SocialWall\Instagram\Model\Media as InstagramMedia;
use C2iS\SocialWall\Facebook\Model\Attachment as FacebookMedia;
use C2iS\SocialWall\Twitter\Model\Hashtag;
use C2iS\SocialWall\Twitter\Model\Media as TwitterMedia;
use C2iS\SocialWall\Youtube\Model\Thumbnail as YoutubeMedia;
use C2iS\SocialWall\GooglePlus\Model\Attachment as GoogleMedia;
use C2iS\SocialWall\Instagram\Model\SocialItem as InstagramSocialItem;
use C2iS\SocialWall\Facebook\Model\SocialItem as FacebookSocialItem;
use C2iS\SocialWall\Twitter\Model\SocialItem as TwitterSocialItem;
use C2iS\SocialWall\Youtube\Model\SocialItem as YoutubeSocialItem;
use C2iS\SocialWall\Flickr\Model\SocialItem as FlickrSocialItem;
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
        $dest->setMessage($source->getMessageHtml());
        $dest->setType($source->getSocialNetwork());

        if ($source->getUser() && !$dest->getUser()) {
            $dest->setUser($this->transformUser($source->getUser()));
        }

        if ($source instanceof InstagramSocialItem) {
            $dest->setLatitude($source->getLatitude());
            $dest->setLongitude($source->getLongitude());
            $dest->setLikes(count($source->getLikes()));
            $dest->setComments(count($source->getComments()));

            if (!$dest->getMedias()->count()) {
                foreach ($source->getImages() as $image) {
                    $dest->addMedia($this->transformInstagramMedia($image));
                }

                foreach ($source->getVideos() as $video) {
                    $dest->addMedia($this->transformInstagramMedia($video));
                }
            }

            if (!$dest->getTags()->count()) {
                foreach ($source->getTags() as $tag) {
                    $dest->addTag($this->transformInstagramTag($tag));
                }
            }
        }

        if ($source instanceof FacebookSocialItem) {
            $dest->setLikes(count($source->getLikes()));
            $dest->setComments(count($source->getComments()));

            if (!$dest->getMedias()->count()) {
                foreach ($source->getImages() as $image) {
                    $dest->addMedia($this->transformFacebookMedia($image));
                }
            }
        }

        if ($source instanceof TwitterSocialItem) {
            $dest->setLikes($source->getFavoriteCount());
            $dest->setComments(count($source->getReply()));

            if (!$dest->getMedias()->count()) {
                foreach ($source->getMedias() as $image) {
                    $dest->addMedia($this->transformTwitterMedia($image));
                }
            }

            if (!$dest->getTags()->count()) {
                foreach ($source->getHashtags() as $tag) {
                    $dest->addTag($this->transformTwitterTag($tag));
                }
            }
        }

        if ($source instanceof YoutubeSocialItem) {
            $dest->setLikes($source->getLikes());
            $dest->setComments($source->getComments());

            if (!$dest->getMedias()->count()) {
                foreach ($source->getThumbnails() as $image) {
                    $dest->addMedia($this->transformYoutubeMedia($image));
                }
            }
        }

        if ($source instanceof GoogleSocialItem) {
            $dest->setLikes($source->getPlusOners());
            $dest->setComments($source->getReplies());

            if (!$dest->getMedias()->count()) {
                foreach ($source->getAttachments() as $image) {
                    $dest->addMedia($this->transformGoogleMedia($image));
                }
            }
        }

        if ($source instanceof FlickrSocialItem) {
            if (!$dest->getMedias()->count()) {
                $dest->addMedia($media = new Media());
                $media->setType('image');
                $media->setLink($source->getUrl());
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
     * @param \C2iS\SocialWall\Instagram\Model\Media    $source
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
     * @param \C2iS\SocialWall\Facebook\Model\Attachment     $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Media|null $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Media
     */
    public function transformFacebookMedia(FacebookMedia $source, Media $dest = null)
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
     * @param \C2iS\SocialWall\Twitter\Model\Media           $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Media|null $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Media
     */
    public function transformTwitterMedia(TwitterMedia $source, Media $dest = null)
    {
        if (null === $dest) {
            $dest = new Media();
        }

        $dest->setLink($source->getMediaSecureUrl());
        $dest->setType($source->getType());

        return $dest;
    }

    /**
     * @param \C2iS\SocialWall\Youtube\Model\Thumbnail       $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Media|null $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Media
     */
    public function transformYoutubeMedia(YoutubeMedia $source, Media $dest = null)
    {
        if (null === $dest) {
            $dest = new Media();
        }

        $dest->setLink($source->getUrl());
        $dest->setWidth($source->getWidth());
        $dest->setHeight($source->getHeight());
        $dest->setType('image"');

        return $dest;
    }

    /**
     * @param \C2iS\SocialWall\GooglePlus\Model\Attachment   $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Media|null $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Media
     */
    public function transformGoogleMedia(GoogleMedia $source, Media $dest = null)
    {
        if (null === $dest) {
            $dest = new Media();
        }

        $dest->setLink($source->getImage());
        $dest->setType('image"');

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

    /**
     * @param \C2is\SocialWall\Twitter\Model\Hashtag  $source
     * @param \C2is\Bundle\SocialWallBundle\Model\Tag $dest
     *
     * @return \C2is\Bundle\SocialWallBundle\Model\Tag
     */
    public function transformTwitterTag(Hashtag $source, Tag $dest = null)
    {
        if (null === $dest) {
            $dest = new Tag();
        }

        $dest->setName($source->getText());

        return $dest;
    }
}
