UPGRADE FROM 2.0.1 to 2.0.2
===========================

### General

  * An additional configuration has been included:

    ```yaml
    google:
        analytics:
            enhanced_ecommerce: false  
    ```

    For integrations where enhanced ecommerce is enabled, 
    the enhanced ecommerce should be attached

    ```yaml
    google:
        analytics:
            trackers:
                default:
                    plugins:
            	        - 'ec'
    ```

### Features

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
