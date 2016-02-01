# SocialWallBundle

Installation
============

Install with composer:

```
  composer require c2is/socialwall-bundle dev-master
```

Add the bundle to your AppKernel:
```php
class AppKernel extends Kernel
{
  public function registerBundles()
  {
    $bundles = array(
      new C2is\Bundle\SocialWallBundle\C2isSocialWallBundle(),
    );

    return $bundles;
  }
}
```

Configuration reference
=======================

```yaml
    c2is_social_wall:
        social_networks:
            facebook:

                # Used to fetch posts and number of followers of a specific user
                user_id:              ~ # Required
                limit:                50
                api:
                    api_key:              ~ # Required
                    api_secret:           ~
            twitter:

                # Used to fetch posts with the given tags
                tags:                 []

                # Used to fetch tweets from a specific user
                user:              ~

                # Used to fetch number of tweets and number of followers of a specific user
                user_id:              ~
                limit:                50
                api:
                    api_key:              ~ # Required
                    api_secret:           ~
            flickr:

                # Used to fetch posts with the given tags
                tags:                 []

                # Used to fetch posts number of followers of a specific user
                user_id:              ~
                limit:                50
                api:
                    api_key:              ~ # Required
                    api_secret:           ~
            instagram:

                # Used to fetch posts with the given tag
                tag:                  ~

                # Used to fetch posts number of followers of a specific user
                user_id:              ~
                limit:                50
                api:
                    api_key:              ~ # Required
                    api_secret:           ~
            google_plus:

                # Used to fetch posts number of followers of a specific user
                user_id:              ~ # Required
                limit:                50
                api:
                    api_key:              ~ # Required
                    api_secret:           ~
            youtube:

                # Used to fetch posts number of followers of a specific user
                channel_id:           ~

                # Used to fetch posts number of followers of a specific playlist
                playlist_id:          ~
                limit:                50
                api:
                    api_key:              ~ # Required
                    api_secret:           ~
        cache:
            duration:             '3600'
```

Usage
=====

This bundle exposes a service providing helper methods to access the SocialWall component:

```php
class MyController
{
  public function myAction()
  {
    $socialWall = $this->get('c2is.social_wall.manager');

    /* Those methods will throw an exception if the social network is not properly configured */
    $twitterItems = $socialWall->getTwitterItemsForUser($overrideUser /* if omitted, uses the user_id defined in the configuration */,, $overrideLimit /* if omitted, uses the limit defined in the configuration */,);
    $twitterItems = $socialWall->getTwitterItemsForTags($overrideTags /* if omitted, uses the tags defined in the configuration */, $overrideLimit /* if omitted, uses the limit defined in the configuration */);
  }
}
```

Facebook
--------

Available methods:

- getFacebookItems($userId = null, $limit = null)
  If userId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* posts from the user *$userId* timeline.
- getFacebookNumberOfSubscribers($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of people who liked the facebook page of the user *$userId*.

    0 => test,
    1 => test,

Twitter
-------

Available methods:

- getTwitterItemsForUser($userId = null, $limit = null)
  If userId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* tweets from the user *$userId*.
- getTwitterItemsForTags(array $tags = null, $limit = null)
  If tags or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* tweets having at least one of the *tags* tag.
- getTwitterNumberOfItems($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of tweets made by the user *$userId*.
- getTwitterNumberOfSubscribers($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of people following user *$userId*.

Flickr
------

Available methods:

- getFlickrItemsForUser($userId = null, $limit = null)
  If userId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* flickr images posted by the user *$userId*.
- getFlickrItemsForTags(array $tags = null, $limit = null)
  If tags or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* flickr images having at least one of the *tags* tag.
- getFlickrNumberOfItems($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of flickr images posted by the user *$userId*.

Instagram
---------

Available methods:

- getInstagramItemsForUser($userId = null, $limit = null)
  If userId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* instagram posts from the user *$userId*.
- getInstagramItemsForTags(array $tags = null, $limit = null)
  If tags or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* instagram posts having at least one of the *tags* tag.
- getInstagramNumberOfItems($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of instagram iamges posted by the user *$userId*.
- getInstagramNumberOfSubscribers($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of people following user *$userId*.

Google Plus
-----------

Available methods:

- getGooglePlusItems($userId = null, $limit = null)
  If userId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* Google Plus posts from the user *$userId* timeline.
- getGooglePlusNumberOfSubscribers($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of people who have the user *$userId* in their circles.

Youtube
-------

Available methods:

- getYoutubeItemsForChannel($channelId = null, $limit = null)
  If channelId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* videos from the *$channelId* channel.
- getYoutubeItemsForPlaylist($playlistId = null, $limit = null)
  If playlistId or limit are omitted, uses the values defined in the configuration.
  Returns *$limit* videos from the *$playlistId* playlist.
- getYoutubeNumberOfSubscribers($userId = null)
  If userId is omitted, uses the value defined in the configuration.
  Returns the number of video views for user *$userId*.
