GoogleBundle
============

The GoogleBundle adds the ability to add various Google services to your application.
These include Google Analytics, Adwords and Static Maps.

Installation
============

Add the following to your composer.json file:

```json
{
    "require": {
        "antimattr/google-bundle": "~1.0@stable"
    }
}
```

Install the libraries by running:

```bash
composer install
```

If everything worked, the Content MongoDB Bundle can now be found at vendor/antimattr/google-bundle.

Finally, be sure to enable the bundle in AppKernel.php by including the following:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        //...
        new AntiMattr\GoogleBundle\GoogleBundle(),
    );
}
```

Configuration
=============

Google Analytics

```yaml
google:
    analytics:
        session_auto_started: true
        trackers:
            shop:
                name: MyJavaScriptCompatibleVariableNameWithNoSpaces
                accountId: UA-xxxxxx-xx
                domain: .mdomain.com
                setSiteSpeedSampleRate: 5
                allowAnchor: true
                allowHash: true
                includeNamePrefix: false
        whitelist: [ 'q', 'utm_source', 'utm_medium', 'utm_term', 'utm_content', 'utm_campaign' ]
```

#### View

Include the Google Analytics Async template in the `head` tag or just before the `</body>` of your layout (The template will lazy load _gaq).

With twig:

    {% include "GoogleBundle:Analytics:async.html.twig" %}

#### Features

##### Logging a Default Page View

    Requires no additional code

##### Sending a Custom Page View

    $this->container()->get('google.analytics')->setCustomPageView('/profile/'.$username);

##### Adding to Page View Queue

Note: Page View Queue is always executed before a Custom Page View

    $this->container()->get('google.analytics')->enqueuePageView('/my-first-page-view-in-queue');
    $this->container()->get('google.analytics')->enqueuePageView('/my-second-page-view-in-queue');

##### Ecommerce Tracking

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

### Google Adwords

#### Application config.yml

Enable loading of the Google Adwords service by adding the following to
the applications's `config.yml` file:

    google:
        adwords:
            conversions:
                account_create:
                    id:    111111
                    label: accountCreateLabel
                    value: 0
                checkout_thanks:
                    id:    222222
                    label: checkoutThanksLabel
                    value: 0

#### Controller

    $this->get('google.adwords')->activateConversionByKey('account_create');

#### View

Include the Google Adwords tracking template like this

    {% include "GoogleBundle:Adwords:track.html.twig" %}

### Google Maps - Static Map

#### Application config.yml

Enable loading of the Google Maps Static service by adding the following to
the applications's `config.yml` file (The static service does NOT require an API Key):

    google:
        maps: ~

#### Controller

    use AntiMattr\GoogleBundle\Maps\StaticMap;
    use AntiMattr\GoogleBundle\Maps\Marker;

    ...

    $map = new StaticMap();
    $map->setId("Paul");
    $map->setSize("512x512");
    $marker = new Marker();
    $marker->setLatitude(40.596631);
    $marker->setLongitude(-73.972359);
    $map->addMarker($marker);
    $this->container->get('google.maps')->addMap($map);

#### View

Include the Google Maps in your template like this:

    {% if google_maps.hasMaps() %}
		{% for map in google_maps.getMaps() %}
			{% autoescape false %}
				{{ map.render }}
			{% endautoescape %}
		{% endfor %}
	{% endif %}
