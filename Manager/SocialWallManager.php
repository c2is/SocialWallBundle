<?php

namespace C2is\Bundle\SocialWallBundle\Manager;

use C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException;
use C2iS\SocialWall\Cache\CacheProviderInterface;
use C2iS\SocialWall\SocialWall;
use C2iS\SocialWall\Model\SocialItemResult;

/**
 * Class SocialWallManager
 *
 * @package C2is\Bundle\SocialWallBundle\Manager
 */
class SocialWallManager
{
    /** @var \C2iS\SocialWall\SocialWall */
    protected $socialWall;

    /** @var array */
    protected $config;

    /**
     * @param \C2iS\SocialWall\SocialWall                   $socialWall
     * @param \C2iS\SocialWall\Cache\CacheProviderInterface $cacheProvider
     * @param array                                         $config
     *
     * @throws \C2iS\SocialWall\Exception\SocialNetworkNotRegisteredException
     */
    public function __construct(SocialWall $socialWall, CacheProviderInterface $cacheProvider, array $config)
    {
        $this->socialWall = $socialWall;
        $this->config     = $config;

        foreach ($this->config['social_networks'] as $key => $networkConf) {
            $networkName      = $networkConf['name'];
            $networkApiKey    = $networkConf['api_key'];
            $networkApiSecret = isset($networkConf['api_secret']) ? $networkConf['api_secret'] : null;
            $this->socialWall->initiateNetwork($networkName, $networkApiKey, $networkApiSecret);
        }

        $this->socialWall->setCacheProvider($cacheProvider);
    }

    /**
     * @param string $userId
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getFacebookItems($userId = null, $limit = null)
    {
        $facebook = $this->socialWall->getNetwork('facebook');
        $config   = $this->config['social_networks']['facebook'];

        if (!$userId && !isset($config['user_id']) || !($userId = $config['user_id'])) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for facebook.'
            );
        }

        $results = $facebook->getItemsForUser(
            array(
                'user_id' => $userId,
                'limit'   => null !== $limit ? $limit : $config['limit'],
                'lang'    => 'fr_FR',
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getFacebookNumberOfSubscribers($userId = null)
    {
        $facebook = $this->socialWall->getNetwork('facebook');
        $config   = $this->config['social_networks']['facebook'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for facebook.'
            );
        }

        return $facebook->getNumberOfSubscribers(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getTwitterItemsForUser($userId = null, $limit = null)
    {
        $twitter = $this->socialWall->getNetwork('twitter');
        $config  = $this->config['social_networks']['twitter'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for twitter.'
            );
        }

        $results = $twitter->getItemsForUser(
            array(
                'user_id' => $userId,
                'limit'   => null !== $limit ? $limit : $config['limit'],
                'lang'    => 'fr',
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param array $tags
     * @param int   $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getTwitterItemsForTags(array $tags = null, $limit = null)
    {
        $twitter = $this->socialWall->getNetwork('twitter');
        $config  = $this->config['social_networks']['twitter'];

        if (!$tags && !(isset($config['tags']) && ($tags = $config['tags']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "tags". You must pass this parameter to the method or add it to the "tags" configuration option for twitter.'
            );
        }

        $results = $twitter->getItemsForTag(
            array(
                'query' => $tags,
                'limit' => null !== $limit ? $limit : $config['limit'],
                'lang'  => 'fr',
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getTwitterNumberOfItems($userId = null)
    {
        $twitter = $this->socialWall->getNetwork('twitter');
        $config  = $this->config['social_networks']['twitter'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for twitter.'
            );
        }

        return $twitter->getNumberOfItems(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getTwitterNumberOfSubscribers($userId = null)
    {
        $twitter = $this->socialWall->getNetwork('twitter');
        $config  = $this->config['social_networks']['twitter'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for twitter.'
            );
        }

        return $twitter->getNumberOfSubscribers(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getFlickrItemsForUser($userId = null, $limit = null)
    {
        $flickr = $this->socialWall->getNetwork('flickr');
        $config = $this->config['social_networks']['flickr'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for flickr.'
            );
        }

        $results = $flickr->getItemsForUser(
            array(
                'user_id' => $userId,
                'limit'   => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param array $tags
     * @param int   $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getFlickrItemsForTags(array $tags = null, $limit = null)
    {
        $flickr = $this->socialWall->getNetwork('flickr');
        $config = $this->config['social_networks']['flickr'];

        if (!$tags && !(isset($config['tags']) && ($tags = $config['tags']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "tags". You must pass this parameter to the method or add it to the "tags" configuration option for flickr.'
            );
        }

        $results = $flickr->getItemsForUser(
            array(
                'tags'  => $tags,
                'limit' => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getFlickrNumberOfItems($userId = null)
    {
        $flickr = $this->socialWall->getNetwork('flickr');
        $config = $this->config['social_networks']['flickr'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for flickr.'
            );
        }

        return $flickr->getNumberOfItems(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getFlickrNumberOfSubscribers($userId = null)
    {
        $flickr = $this->socialWall->getNetwork('flickr');
        $config = $this->config['social_networks']['flickr'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for flickr.'
            );
        }

        return $flickr->getNumberOfSubscribers(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getInstagramItemsForUser($userId = null, $limit = null)
    {
        $instagram = $this->socialWall->getNetwork('instagram');
        $config    = $this->config['social_networks']['instagram'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for instagram.'
            );
        }

        $results = $instagram->getItemsForUser(
            array(
                'user_id' => $userId,
                'limit'   => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $tag
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getInstagramItemsForTag($tag = null, $limit = null)
    {
        $instagram = $this->socialWall->getNetwork('instagram');
        $config    = $this->config['social_networks']['instagram'];

        if (!$tag && !(isset($config['tag']) && ($tag = $config['tag']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "tag". You must pass this parameter to the method or add it to the "tag" configuration option for instagram.'
            );
        }

        $results = $instagram->getItemsForTag(
            array(
                'tag'   => $tag,
                'limit' => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getInstagramNumberOfItems($userId = null)
    {
        $instagram = $this->socialWall->getNetwork('instagram');
        $config    = $this->config['social_networks']['instagram'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for instagram.'
            );
        }

        return $instagram->getNumberOfItems(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getInstagramNumberOfSubscribers($userId = null)
    {
        $instagram = $this->socialWall->getNetwork('instagram');
        $config    = $this->config['social_networks']['instagram'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for instagram.'
            );
        }

        return $instagram->getNumberOfSubscribers(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $userId
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getGooglePlusItems($userId = null, $limit = null)
    {
        $googlePlus = $this->socialWall->getNetwork('google_plus');
        $config     = $this->config['social_networks']['google_plus'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for google_plus.'
            );
        }

        $results = $googlePlus->getItemsForUser(
            array(
                'user_id' => $userId,
                'limit'   => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getGooglePlusNumberOfSubscribers($userId = null)
    {
        $googlePlus = $this->socialWall->getNetwork('google_plus');
        $config     = $this->config['social_networks']['google_plus'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for google_plus.'
            );
        }

        return $googlePlus->getNumberOfSubscribers(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param string $channelId
     * @param int    $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getYoutubeItemsForChannel($channelId = null, $limit = null)
    {
        $youtube = $this->socialWall->getNetwork('youtube');
        $config  = $this->config['social_networks']['youtube'];

        if (!$channelId && !(isset($config['channel_id']) && ($channelId = $config['channel_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "channel_id". You must pass this parameter to the method or add it to the "channel_id" configuration option for youtube.'
            );
        }

        $results = $youtube->getItemsForUser(
            array(
                'channelId' => $channelId,
                'limit'     => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param null $limit
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getYoutubeItemsForPlaylist($playlistId = null, $limit = null)
    {
        $youtube = $this->socialWall->getNetwork('youtube');
        $config  = $this->config['social_networks']['youtube'];

        if (!$playlistId && !(isset($config['playlist_id']) && ($playlistId = $config['playlist_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "playlist_id". You must pass this parameter to the method or add it to the "playlist_id" configuration option for youtube.'
            );
        }

        $results = $youtube->getItemsForTag(
            array(
                'playlistId' => $playlistId,
                'limit'      => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws \C2is\Bundle\SocialWallBundle\Exception\InvalidConfigurationException
     */
    public function getYoutubeNumberOfSubscribers($userId = null)
    {
        $youtube = $this->socialWall->getNetwork('youtube');
        $config  = $this->config['social_networks']['youtube'];

        if (!$userId && !(isset($config['user_id']) && ($userId = $config['user_id']))) {
            throw new InvalidConfigurationException(
                'Missing required parameter "user_id". You must pass this parameter to the method or add it to the "user_id" configuration option for youtube.'
            );
        }

        return $youtube->getNumberOfSubscribers(
            array(
                'user_id' => $userId,
            )
        );
    }

    /**
     * @param \C2iS\SocialWall\Model\SocialItemResult|bool $results
     * @param int|null                                     $limit
     *
     * @return array
     */
    protected function getItems($results, $limit = null)
    {
        if (false === $results || !$results instanceof SocialItemResult) {
            return array();
        }

        $items = $results->getItems();

        return null !== $limit ? array_slice($items, 0, $limit) : $items;
    }
}
