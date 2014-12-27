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
        "antimattr/google-bundle": "~2.0@stable"
    }
}
```

Install the libraries by running:

```bash
composer install
```

If everything worked, the Google Bundle can now be found at vendor/antimattr/google-bundle.

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
        enhanced_ecommerce: false
        session_auto_started: true
        trackers:
            default:
                name: MyJavaScriptCompatibleVariableNameWithNoSpaces
                accountId: UA-xxxxxx-xx
                domain: .mdomain.com
                setSiteSpeedSampleRate: 5
                allowAnchor: true
                allowHash: true
                includeNamePrefix: false
                plugins:
                    - 'linkid'
        whitelist: [ 'q', 'utm_source', 'utm_medium', 'utm_term', 'utm_content', 'utm_campaign' ]
```

#### View

Include the Google Analytics Async template in the `head` tag or just before the `</body>` of your layout (The template will lazy load _gaq).

With twig:

    {% include "GoogleBundle:Analytics:async_universal.html.twig" %}

Features
========

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

#### Enhanced Ecommerce Tracking 

Measuring Transactions

https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#measuring-transactions

    $transaction = new \AntiMattr\GoogleBundle\Analytics\Transaction();
    $transaction->setOrderNumber('xxxx');
    $transaction->setAffiliation('Store 777');
    $transaction->setRevenue(100.00); // <== NEW    
    $transaction->setTotal(100.00);
    $transaction->setTax(10.00);
    $transaction->setShipping(5.00);
    $transaction->setCity("NYC");
    $transaction->setState("NY");
    $transaction->setCountry("USA");
    $this->get('google.analytics')->setTransaction($transaction);

    $product = new \AntiMattr\GoogleBundle\Analytics\Item();
    $product->setSku('zzzz');
    $product->setTitle('Product X');
    $product->setAction('purchase');    
    $product->setBrand('Brand AA');
    $product->setCategory('Category A');
    $product->setPrice(50.00);
    $product->setQuantity(1);
    $product->setVariant('Black');
    $product->setCoupon('COUPON AAA');
    $product->setPosition(1);
    $this->get('google.analytics')->addItem($product);

    $product = new \AntiMattr\GoogleBundle\Analytics\Item();
    $product->setOrderNumber('bbbb');
    $product->setSku('jjjj');
    $product->setTitle('Product Y');
    $product->setAction('purchase');    
    $product->setBrand('Brand BB');    
    $product->setCategory('Category B');
    $product->setPrice(25.00);
    $product->setQuantity(2);
    $product->setVariant('Yellow');
    $product->setCoupon('COUPON BBB');
    $product->setPosition(2);    
    $this->get('google.analytics')->addItem($product);

Measuring Impressions

https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#measuring-impressions

    $impression = new \AntiMattr\GoogleBundle\Analytics\Impression();
    $impression->setSku('zzzz');
    $impression->setTitle('Product X');
    $impression->setAction('detail');    
    $impression->setBrand('Brand AA');
    $impression->setCategory('Category A');
    $impression->setPrice(50.00);
    $impression->setVariant('Black');
    $impression->setList('Search Results Page 1');
    $impression->setPosition(1);
    $this->get('google.analytics')->addImpression($impression);

    $impression = new \AntiMattr\GoogleBundle\Analytics\Impression();
    $impression->setOrderNumber('bbbb');
    $impression->setSku('jjjj');
    $impression->setTitle('Product Y');
    $impression->setAction('detail');    
    $impression->setBrand('Brand BB');    
    $impression->setCategory('Category B');
    $impression->setPrice(25.00);
    $impression->setVariant('Yellow');
    $impression->setList('Search Results Page 2');
    $impression->setPosition(2);    
    $this->get('google.analytics')->addImpression($impression);

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
