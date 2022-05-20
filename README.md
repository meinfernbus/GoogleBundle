[![Build Status](https://travis-ci.org/meinfernbus/GoogleBundle.svg?branch=master)](https://travis-ci.org/meinfernbus/GoogleBundle)

# GoogleBundle

The GoogleBundle adds the ability to add various google-related services
to your application. These include Google Analytics, Adwords and Static Maps.
This module was forked a long time ago from https://github.com/antimattr/GoogleBundle but is now maintained as a separate project.

## Installation

### Composer
composer require meinfernbus/google-bundle

### Application Kernel

Add GoogleBundle to the `registerBundles()` method of your application kernel:

```php
    public function registerBundles()
    {
        return array(
            new AntiMattr\GoogleBundle\GoogleBundle(),
        );
    }
```

## Configuration

### Google Analytics

#### Application config.yml

Enable loading of the Google Analytics service by adding the following to
the application's `config.yml` file:

```yaml
    google:
        analytics:
            trackers:
                default:
                    name:      MyJavaScriptCompatibleVariableNameWithNoSpaces
                    accountId: UA-xxxx-x
                    domain:    .mydomain.com
                    trackPageLoadTime: true
                    anonymizeIp: false
```

#### View

Include the Google Analytics Async template in the `head` tag or just before the `</body>` of your layout (The template will lazy load _gaq).

With twig:

```twig
    {% include "@Google/Analytics/async.html.twig" %}
```

#### Features

##### Logging a Default Page View

    Requires no additional code

##### Sending a Custom Page View

```php
    $this->container()->get('google.analytics')->setCustomPageView('/profile/'.$username);
```

##### Adding to Page View Queue

Note: Page View Queue is always executed before a Custom Page View

```php
    $this->container()->get('google.analytics')->enqueuePageView('/my-first-page-view-in-queue');
    $this->container()->get('google.analytics')->enqueuePageView('/my-second-page-view-in-queue');
```

##### Ecommerce Tracking

```php
    $transaction = new \AntiMattr\GoogleBundle\Analytics\Transaction();
    $transaction->setOrderNumber('xxxx');
    $transaction->setAffiliation('Store 777');
    $transaction->setTotal(100.00);
    $transaction->setTax(10.00);
    $transaction->setShipping(5.00);
    $transaction->setCity("NYC");
    $transaction->setState("NY");
    $transaction->setCountry("USA");
    $this->get('google.analytics')->setTransaction($transaction);

    $item = new \AntiMattr\GoogleBundle\Analytics\Item();
    $item->setOrderNumber('xxxx');
    $item->setSku('zzzz');
    $item->setName('Product X');
    $item->setCategory('Category A');
    $item->setPrice(50.00);
    $item->setQuantity(1);
    $this->get('google.analytics')->addItem($item);

    $item = new \AntiMattr\GoogleBundle\Analytics\Item();
    $item->setOrderNumber('bbbb');
    $item->setSku('jjjj');
    $item->setName('Product Y');
    $item->setCategory('Category B');
    $item->setPrice(25.00);
    $item->setQuantity(2);
    $this->get('google.analytics')->addItem($item);
```

### Google Adwords

#### Application config.yml

Enable loading of the Google Adwords service by adding the following to
the applications's `config.yml` file:

```yaml
    google:
        adwords:
            conversions:
                account_create:
                    id:    111111
                    label: accountCreateLabel
                    value: 0
                    remarketing: false
                checkout_thanks:
                    id:    222222
                    label: checkoutThanksLabel
                    value: 0
                    remarketing: false
                remarketing:
                    id:    333333
                    label: "google-assigned-remarketing-label"
                    value: 0
                    remarketing: true
```

#### Controller

```php
    $this->get('google.adwords')->activateConversionByKey('account_create');
```

#### View

Include the Google Adwords tracking template like this

```twig
    {% include "GoogleBundle:Adwords:track.html.twig" %}
```

### Google Maps - Static Map

#### Application config.yml

Enable loading of the Google Maps Static service by adding the following to
the applications's `config.yml` file:

```yaml
google:
    maps:
        config:
            key: YOUR-API-KEY-FROM-GOOGLE
            host: YOUR-HOST // Host where the gmap image is served.
            (Optional, only needed if script is running from CLI. If not set $_SERVER is used)
            uploadDir: '%kernel.project_dir%/web/maps' // absolute path to directory where map files
            publicDir: '/maps' //http path where map files supposed to be publicly available
           
```

Get your key at https://code.google.com/apis/console/

#### Controller

```php
    use AntiMattr\GoogleBundle\Maps\StaticMap;
    use AntiMattr\GoogleBundle\Maps\Marker;

    ...

    /** @var \AntiMattr\GoogleBundle\MapsManager $googleContainer */
    $googleContainer = $this->container->get('google.maps');
    $map = $googleContainer->createStaticMap();
    $map->setId("Paul");
    $map->setSize("512x512");
    $marker = new Marker();
    $marker->setLatitude(40.596631);
    $marker->setLongitude(-73.972359);
    $map->addMarker($marker);
    $googleContainer->addMap($map);
```

#### View

Include the Google Maps in your template like this:

```twig
    {% if google_maps.hasMaps() %}
		{% for map in google_maps.getMaps() %}
			{% autoescape false %}
				{{ map.render }}
			{% endautoescape %}
		{% endfor %}
	{% endif %}
```
