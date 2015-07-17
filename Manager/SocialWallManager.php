<?php

namespace C2is\Bundle\SocialWallBundle\Manager;

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
     * @return array
     * @throws \C2iS\SocialWall\Exception\InvalidParametersException
     */
    public function getFacebookItems($limit = null)
    {
        $facebook = $this->socialWall->getNetwork('facebook');
        $config   = $this->config['social_networks']['facebook'];
        $results  = $facebook->getSocialItems(
            array(
                'user_id' => $config['query'],
                'limit'   => null !== $limit ? $limit : $config['limit'],
                'lang'    => 'fr_FR',
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @return array
     * @throws \C2iS\SocialWall\Exception\InvalidParametersException
     */
    public function getTwitterItems($limit = null)
    {
        $twitter = $this->socialWall->getNetwork('twitter');
        $config  = $this->config['social_networks']['twitter'];
        $results = $twitter->getSocialItems(
            array(
                'query' => explode(',', $config['query']),
                'limit' => null !== $limit ? $limit : $config['limit'],
                'lang'  => 'fr',
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @return array
     * @throws \C2iS\SocialWall\Exception\InvalidParametersException
     */
    public function getFlickrItems($limit = null)
    {
        $flickr  = $this->socialWall->getNetwork('flickr');
        $config  = $this->config['social_networks']['flickr'];
        $results = $flickr->getSocialItems(
            array(
                'tags'  => $config['query'],
                'limit' => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @return array
     * @throws \C2iS\SocialWall\Exception\InvalidParametersException
     */
    public function getInstagramItems($limit = null)
    {
        $instagram = $this->socialWall->getNetwork('instagram');
        $config    = $this->config['social_networks']['instagram'];
        $results   = $instagram->getSocialItems(
            array(
                'tag'   => $config['query'],
                'limit' => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @return array
     * @throws \C2iS\SocialWall\Exception\InvalidParametersException
     */
    public function getGooglePlusItems($limit = null)
    {
        $googlePlus = $this->socialWall->getNetwork('google_plus');
        $config     = $this->config['social_networks']['google_plus'];
        $results    = $googlePlus->getSocialItems(
            array(
                'user_id' => $config['query'],
                'limit'   => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @return array
     * @throws \C2iS\SocialWall\Exception\InvalidParametersException
     */
    public function getYoutubeItems($limit = null)
    {
        $youtube = $this->socialWall->getNetwork('youtube');
        $config  = $this->config['social_networks']['youtube'];
        $results = $youtube->getSocialItems(
            array(
                'playlistId' => $config['query'],
                'limit'      => null !== $limit ? $limit : $config['limit'],
            )
        );

        return $this->getItems($results, $limit);
    }

    /**
     * @param string $networkName
     * @param null   $limit
     *
     * @return array
     */
    public function getItemsForNetwork($networkName, $limit = null)
    {
        switch ($networkName) {
            case 'facebook':
                return $this->getFacebookItems($limit);
            case 'twitter':
                return $this->getTwitterItems($limit);
            case 'instagram':
                return $this->getInstagramItems($limit);
            case 'flickr':
                return $this->getFlickrItems($limit);
            case 'google_plus':
                return $this->getGooglePlusItems($limit);
            case 'youtube':
                return $this->getYoutubeItems($limit);
        }

        return array();
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
