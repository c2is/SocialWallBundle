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

Configuration
=============

```yaml
  c2is_social_wall:
    social:
      name: 'social' # Slug of a social network managed by this bundle (can be one of facebook, twitter, google_plus, youtube, flickr, instagram)
      api_key: 'MyAPIKey' # Required
      api_secret: 'MyAPISecret' # Optional: If applicable
      limit: 50 # Optional, default value is 50
      query: 'search query' # For twitter, a list of tags (#mytag #myothertag), for facebook a user ID, for youtube a playlist ID, for others a keyword)
    cache:
      duration: 3600 # Optional, default value is 3600
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
    $facebookItems = $socialWall->getItemsForNetwork('facebook');
    $twitterItems = $socialWall->getTwitterItems();
  }
}
```
