<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Analytics\CustomVariable;
use AntiMattr\GoogleBundle\Analytics\Event;
use AntiMattr\GoogleBundle\Analytics\Impression;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Product;
use AntiMattr\GoogleBundle\Analytics\Transaction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Analytics
{
    const EC_IMPRESSIONS_KEY   = 'google_analytics/ec/impressions';
    const EC_PRODUCTS_KEY      = 'google_analytics/ec/products';
    const EVENT_QUEUE_KEY      = 'google_analytics/event/queue';
    const CUSTOM_PAGE_VIEW_KEY = 'google_analytics/page_view';
    const PAGE_VIEW_QUEUE_KEY  = 'google_analytics/page_view/queue';
    const TRANSACTION_KEY      = 'google_analytics/transaction';
    const ITEMS_KEY            = 'google_analytics/items';

    private $container;
    private $customVariables = array();
    private $enhancedEcommerce = false;
    private $pageViewsWithBaseUrl = true;
    private $sessionAutoStarted = false;
    private $trackers;
    private $whitelist;
    private $api_key;
    private $client_id;
    private $table_id;

    public function __construct(
        ContainerInterface $container,
        array $trackers = array(),
        array $whitelist = array(),
        array $dashboard = array(),
        $sessionAutoStarted = false,
        $enhancedEcommerce = false)
    {
        $this->container = $container;
        $this->enhancedEcommerce = $enhancedEcommerce;
        $this->sessionAutoStarted = $sessionAutoStarted;
        $this->trackers = $trackers;
        $this->whitelist = $whitelist;
        $this->api_key = isset($dashboard['api_key']) ? $dashboard['api_key'] : '';
        $this->client_id = isset($dashboard['client_id']) ? $dashboard['client_id'] : '';
        $this->table_id = isset($dashboard['table_id']) ? $dashboard['table_id'] : '';
    }

    /**
     * @return boolean
     */
    public function excludeBaseUrl()
    {
        $this->pageViewsWithBaseUrl = false;
    }

    /**
     * @return boolean
     */
    public function includeBaseUrl()
    {
        $this->pageViewsWithBaseUrl = true;
    }

    /**
     * @param string $trackerKey
     * @param boolean $allowAnchor
     */
    public function setAllowAnchor($trackerKey, $allowAnchor)
    {
        $this->setTrackerProperty($trackerKey, 'allowAnchor', $allowAnchor);
    }

    /**
     * @param string $trackerKey
     *
     * @return boolean $allowAnchor (default:false)
     */
    public function getAllowAnchor($trackerKey)
    {
        if (null === ($property = $this->getTrackerProperty($trackerKey, 'allowAnchor'))) {
            return false;
        }
        return $property;
    }

    /**
     * @param string $trackerKey
     * @param boolean $allowHash
     */
    public function setAllowHash($trackerKey, $allowHash)
    {
        $this->setTrackerProperty($trackerKey, 'allowHash', $allowHash);
    }

    /**
     * @param string $trackerKey
     *
     * @return boolean $allowHash (default:false)
     */
    public function getAllowHash($trackerKey)
    {
        if (null === ($property = $this->getTrackerProperty($trackerKey, 'allowHash'))) {
            return false;
        }
        return $property;
    }

    /**
     * @param string $trackerKey
     * @param boolean $allowLinker
     */
    public function setAllowLinker($trackerKey, $allowLinker)
    {
        $this->setTrackerProperty($trackerKey, 'allowLinker', $allowLinker);
    }

    /**
     * @param string $trackerKey
     *
     * @return boolean $allowLinker (default:true)
     */
    public function getAllowLinker($trackerKey)
    {
        if (null === ($property = $this->getTrackerProperty($trackerKey, 'allowLinker'))) {
            return true;
        }
        return $property;
    }

    /**
     * @param string $trackerKey
     * @param boolean $includeNamePrefix
     */
    public function setIncludeNamePrefix($trackerKey, $includeNamePrefix)
    {
        $this->setTrackerProperty($trackerKey, 'includeNamePrefix', $includeNamePrefix);
    }

    /**
     * @param string $trackerKey
     *
     * @return boolean $includeNamePrefix (default:true)
     */
    public function getIncludeNamePrefix($trackerKey)
    {
        if (null === ($property = $this->getTrackerProperty($trackerKey, 'includeNamePrefix'))) {
            return true;
        }
        return $property;
    }

    /**
     * @param string $trackerKey
     *
     * @param boolean $name
     */
    public function setTrackerName($trackerKey, $name)
    {
        $this->setTrackerProperty($trackerKey, 'name', $name);
    }

    /**
     * @param string $trackerKey
     *
     * @return string $name
     */
    public function getTrackerName($trackerKey)
    {
        return $this->getTrackerProperty($trackerKey, 'name');
    }

    /**
     * @param string $trackerKey
     *
     * @param int $siteSpeedSampleRate
     */
    public function setSiteSpeedSampleRate($trackerKey, $siteSpeedSampleRate)
    {
        $this->setTrackerProperty($trackerKey, 'setSiteSpeedSampleRate', $siteSpeedSampleRate);
    }

    /**
     * @param string $trackerKey
     *
     * @return int $siteSpeedSampleRate (default:null)
     */
    public function getSiteSpeedSampleRate($trackerKey)
    {
        if (null != ($property = $this->getTrackerProperty($trackerKey, 'setSiteSpeedSampleRate'))) {
            return (int) $property;
        }
    }

    /**
     * @return string $customPageView
     */
    public function getCustomPageView()
    {
        $customPageView = $this->container->get('session')->get(self::CUSTOM_PAGE_VIEW_KEY);
        $this->container->get('session')->remove(self::CUSTOM_PAGE_VIEW_KEY);
        return $customPageView;
    }

    /**
     * @return boolean $hasCustomPageView
     */
    public function hasCustomPageView()
    {
        return $this->has(self::CUSTOM_PAGE_VIEW_KEY);
    }

    /**
     * @param string $customPageView
     */
    public function setCustomPageView($customPageView)
    {
        $this->container->get('session')->set(self::CUSTOM_PAGE_VIEW_KEY, $customPageView);
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\CustomVariable $customVariable
     */
    public function addCustomVariable(CustomVariable $customVariable)
    {
        $this->customVariables[] = $customVariable;
    }

    /**
     * @return array[] AntiMattr\GoogleBundle\Analytics\CustomVariable $customVariables
     */
    public function getCustomVariables()
    {
        return $this->customVariables;
    }

    /**
     * @return boolean
     */
    public function hasCustomVariables()
    {
        if (!empty($this->customVariables)) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function isEnhancedEcommerce()
    {
        return $this->enhancedEcommerce;
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Event $event
     */
    public function enqueueEvent(Event $event)
    {
        $eventArray = $event->toArray();
        $this->add(self::EVENT_QUEUE_KEY, $eventArray);
    }

    /**
     * @param array[] AntiMattr\GoogleBundle\Analytics\Event $eventQueue
     */
    public function getEventQueue()
    {
        $eventArray = $this->getOnce(self::EVENT_QUEUE_KEY);
        $hydratedEvents = array();
        foreach ($eventArray as $value) {
            if (is_object($value)) {
                $hydratedEvents[] = $value;
                continue;
            }
            $event = new Event();
            $event->fromArray($value);
            $hydratedEvents[] = $event;
        }
        return $hydratedEvents;
    }

    /**
     * @return boolean
     */
    public function hasEventQueue()
    {
        return $this->has(self::EVENT_QUEUE_KEY);
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Item $item
     */
    public function addItem(Item $item)
    {
        $itemArray = $item->toArray();
        $this->add(self::ITEMS_KEY, $itemArray);
    }

    /**
     * @return boolean
     */
    public function hasItems()
    {
        return $this->has(self::ITEMS_KEY);
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Item $item
     *
     * @return boolean $hasItem
     */
    public function hasItem(Item $item)
    {
        if (!$this->hasItems()) {
            return false;
        }
        $items = $this->getItemsFromSession();

        $itemSku = $item->getSku();
        foreach ($items as $itemFromSession) {
            if ($itemSku == $itemFromSession->getSku()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array[] AntiMattr\GoogleBundle\Analytics\Item $items
     */
    public function setItems($items)
    {
        $itemsArray = array();
        foreach ($items as $item) {
            $itemArray = $item->toArray();
            $itemsArray[] = $itemArray;
        }
        $this->container->get('session')->set(self::ITEMS_KEY, $itemsArray);
    }

    /**
     * @return array[] AntiMattr\GoogleBundle\Analytics\Item
     */
    public function getItems()
    {
        $itemArray = $this->getOnce(self::ITEMS_KEY);
        $hydratedItems = array();
        foreach ($itemArray as $value) {
            if (is_object($value)) {
                $hydratedItems[] = $value;
                continue;
            }
            $item = new Item();
            $item->fromArray($value);
            $hydratedItems[] = $item;
        }
        return $hydratedItems;
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Impression $impression
     */
    public function addImpression(Impression $impression)
    {
        $impressionArray = $impression->toArray();
        $this->add(self::EC_IMPRESSIONS_KEY . '/'. $impression->getAction(), $impressionArray);
    }

    /**
     * @param string action
     *
     * @return boolean
     */
    public function hasImpressions($action = '')
    {
        return $this->has(self::EC_IMPRESSIONS_KEY . '/' . $action);
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Impression $impression
     *
     * @return boolean
     */
    public function hasImpression(Impression $impression)
    {
        $action = $impression->getAction();
        if (!$this->hasImpressions($action)) {
            return false;
        }
        $impressions = $this->getImpressionsFromSession($action);

        $impressionSku = $impression->getId();
        foreach ($impressions as $impressionFromSession) {
            if ($impressionSku == $impressionFromSession->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array[] AntiMattr\GoogleBundle\Analytics\Impression $impressions
     */
    public function setImpressions($impressions)
    {
        $impressionsArray = array();
        foreach ($impressions as $impression) {
            $action = $impression->getAction();
            $impressionArray = $impression->toArray();
            if (!isset($impressionsArray[$action])) {
                $impressionsArray[$action] = array();
            }
            $impressionsArray[$action][] = $impressionArray;
        }
        foreach ($impressionsArray as $action => $impressionArray) {
            $this->container->get('session')->set(self::EC_IMPRESSIONS_KEY . '/' . $action, $impressionArray);
        }
    }

    /**
     * @param string $action
     *
     * @return array[] AntiMattr\GoogleBundle\Analytics\Impression
     */
    public function getImpressions($action = '')
    {
        $impressionArray = $this->getOnce(self::EC_IMPRESSIONS_KEY . '/' . $action);
        $hydratedImpressions = array();
        foreach ($impressionArray as $value) {
            if (is_object($value)) {
                $hydratedImpressions[] = $value;
                continue;
            }
            $impression = new Impression();
            $impression->fromArray($value);
            $hydratedImpressions[] = $impression;
        }
        return $hydratedImpressions;
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Product $product
     */
    public function addProduct(Product $product)
    {
        $productArray = $product->toArray();
        $this->add(self::EC_PRODUCTS_KEY . '/'. $product->getAction(), $productArray);
    }

    /**
     * @param string action
     *
     * @return boolean
     */
    public function hasProducts($action = '')
    {
        return $this->has(self::EC_PRODUCTS_KEY . '/'. $action);
    }

    /**
     * @param AntiMattr\GoogleBundle\Analytics\Product $product
     *
     * @return boolean
     */
    public function hasProduct(Product $product)
    {
        $action = $product->getAction();
        if (!$this->hasProducts($action)) {
            return false;
        }
        $products = $this->getProductsFromSession($action);

        $productSku = $product->getId();
        foreach ($products as $productFromSession) {
            if ($productSku == $productFromSession->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array[] AntiMattr\GoogleBundle\Analytics\Product $products
     */
    public function setProducts($products)
    {
        $productsArray = array();
        foreach ($products as $product) {
            $action = $product->getAction();
            $productArray = $product->toArray();
            if (!isset($productsArray[$action])) {
                $productsArray[$action] = array();
            }
            $productsArray[$action][] = $productArray;
        }
        foreach ($productsArray as $action => $productArray) {
            $this->container->get('session')->set(self::EC_PRODUCTS_KEY . '/' . $action, $productArray);
        }
    }

    /**
     * @param string $action
     *
     * @return array[] AntiMattr\GoogleBundle\Analytics\Product
     */
    public function getProducts($action = '')
    {
        $productArray = $this->getOnce(self::EC_PRODUCTS_KEY. '/' . $action);
        $hydratedProducts = array();
        foreach ($productArray as $value) {
            if (is_object($value)) {
                $hydratedProducts[] = $value;
                continue;
            }
            $product = new Product();
            $product->fromArray($value);
            $hydratedProducts[] = $product;
        }
        return $hydratedProducts;
    }

    /**
     * @param string $pageView
     */
    public function enqueuePageView($pageView)
    {
        $this->add(self::PAGE_VIEW_QUEUE_KEY, $pageView);
    }

    /**
     * @param array $pageViewQueue
     */
    public function getPageViewQueue()
    {
        return $this->getOnce(self::PAGE_VIEW_QUEUE_KEY);
    }

    /**
     * @return boolean $hasPageViewQueue
     */
    public function hasPageViewQueue()
    {
        return $this->has(self::PAGE_VIEW_QUEUE_KEY);
    }

    /**
     * @param string $trackerKey
     * @param array $plugins
     */
    public function setPlugins($trackerKey, $plugins)
    {
        $this->setTrackerProperty($trackerKey, 'plugins', $plugins);
    }

    /**
     * @param string $trackerKey
     *
     * @return array $plugins array()
     */
    public function getPlugins($trackerKey)
    {
        if (null === ($property = $this->getTrackerProperty($trackerKey, 'plugins'))) {
            return array();
        }
        return $property;
    }

    /**
     * @return Symfony\Component\HttpFoundation\Request $request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * Check and apply base url configuration
     * If a GET param whitelist is declared,
     * Then only allow the whitelist
     *
     * @return string $requestUri
     */
    public function getRequestUri()
    {
        $request = $this->getRequest();
        $path = $request->getPathInfo();

        if (!$this->pageViewsWithBaseUrl) {
            $baseUrl = $request->getBaseUrl();
            if ($baseUrl != '/') {
                $uri = str_replace($baseUrl, '', $path);
            }
        }

        $params = $request->query->all();
        if (!empty($this->whitelist) && !empty($params)) {
            $whitelist = array_flip($this->whitelist);
            $params = array_intersect_key($params, $whitelist);
        }

        $requestUri = $path;
        $query = http_build_query($params);

        if (isset($query) && '' != trim($query)) {
            $requestUri .= '?'. $query;
        }
        return $requestUri;
    }

    /**
     * @return array $trackers
     */
    public function getTrackers(array $trackers = array())
    {
        if (!empty($trackers)) {
            $trackers = array();
            foreach ($trackers as $key) {
                if (isset($this->trackers[$key])) {
                    $trackers[$key] = $this->trackers[$key];
                }
            }
            return $trackers;
        } else {
            return $this->trackers;
        }
    }

    /**
     * @return boolean $isTransactionValid
     */
    public function isTransactionValid()
    {
        if (!$this->hasTransaction()) {
            return false;
        }
        if (null === $this->getTransactionFromSession()->getOrderNumber()) {
            return false;
        }
        if ($this->enhancedEcommerce && null === $this->getTransactionFromSession()->getRevenue()) {
            return false;
        }
        if (!$this->enhancedEcommerce && null === $this->getTransactionFromSession()->getTotal()) {
            return false;
        }

        if ($this->hasItems()) {
            $items = $this->getItemsFromSession();
            foreach ($items as $item) {
                if (!$item->getOrderNumber() || !$item->getSku() || !$item->getPrice() || !$item->getQuantity()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return Transaction $transaction
     */
    public function getTransaction()
    {
        $transaction = $this->getTransactionFromSession();
        $this->container->get('session')->remove(self::TRANSACTION_KEY);
        return $transaction;
    }

    /**
     * @return boolean $hasTransaction
     */
    public function hasTransaction()
    {
        return $this->has(self::TRANSACTION_KEY);
    }

    /**
     * @param Transaction $transaction
     */
    public function setTransaction(Transaction $transaction)
    {
        $transactionArray = $transaction->toArray();
        $this->container->get('session')->set(self::TRANSACTION_KEY, $transactionArray);
    }

    /**
     * @param string
     *
     * @return boolean
     *
     * @throws InvalidArgumentException
     */
    private function isValidConfigKey($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            throw new \InvalidArgumentException(sprintf('There is no tracker configuration assigned with the key "%s".', $trackerKey));
        }
        return true;
    }

    /**
     * @param string $tracker
     * @param string $property
     * @param string $value
     */
    private function setTrackerProperty($tracker, $property, $value)
    {
        if ($this->isValidConfigKey($tracker)) {
            $this->trackers[$tracker][$property] = $value;
        }
    }

    /**
     * @param string $tracker
     * @param string $property
     *
     * @return mixed
     */
    private function getTrackerProperty($tracker, $property)
    {
        if (!$this->isValidConfigKey($tracker)) {
            return;
        }

        if (array_key_exists($property, $this->trackers[$tracker])) {
            return $this->trackers[$tracker][$property];
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    private function add($key, $value)
    {
        $bucket = $this->container->get('session')->get($key, array());
        $bucket[] = $value;
        $this->container->get('session')->set($key, $bucket);
    }

    /**
     * @param string $key
     *
     * @return boolean $hasKey
     */
    private function has($key)
    {
        if (!$this->sessionAutoStarted && !$this->container->get('session')->isStarted()) {
            return false;
        }
        
        $bucket = $this->container->get('session')->get($key, array());
        return !empty($bucket);
    }

    /**
     * @param string $key
     *
     * @return array $value
     */
    private function get($key)
    {
        return $this->container->get('session')->get($key, array());
    }

    /**
     * @param string $key
     *
     * @return array $value
     */
    private function getOnce($key)
    {
        $value = $this->container->get('session')->get($key, array());
        $this->container->get('session')->remove($key);
        return $value;
    }

    /**
     * @return array[] AntiMattr\GoogleBundle\Analytics\Item $items
     */
    private function getItemsFromSession()
    {
        $itemArray = $this->get(self::ITEMS_KEY);
        $hydratedItems = array();
        foreach ($itemArray as $value) {
            if (is_object($value)) {
                $hydratedItems[] = $value;
                continue;
            }
            $item = new Item();
            $item->fromArray($value);
            $hydratedItems[] = $item;
        }
        return $hydratedItems;
    }

    /**
     * @param string $action
     *
     * @return array[] AntiMattr\GoogleBundle\Analytics\Impression $impressions
     */
    private function getImpressionsFromSession($action = '')
    {
        $impressionArray = $this->get(self::EC_IMPRESSIONS_KEY. '/' . $action);
        $hydratedImpressions = array();
        foreach ($impressionArray as $value) {
            if (is_object($value)) {
                $hydratedProducts[] = $value;
                continue;
            }
            $impression = new Impression();
            $impression->fromArray($value);
            $hydratedImpressions[] = $impression;
        }
        return $hydratedImpressions;
    }

    /**
     * @param string $action
     *
     * @return array[] AntiMattr\GoogleBundle\Analytics\Product $products
     */
    private function getProductsFromSession($action = '')
    {
        $productArray = $this->get(self::EC_PRODUCTS_KEY. '/' . $action);
        $hydratedProducts = array();
        foreach ($productArray as $value) {
            if (is_object($value)) {
                $hydratedProducts[] = $value;
                continue;
            }
            $product = new Product();
            $product->fromArray($value);
            $hydratedProducts[] = $product;
        }
        return $hydratedProducts;
    }

    /**
     * @return AntiMattr\GoogleBundle\Analytics\Transaction $transaction
     */
    private function getTransactionFromSession()
    {
        $transactionArray = $this->container->get('session')->get(self::TRANSACTION_KEY);
        if (empty($transactionArray) || is_object($transactionArray)) {
            return $transactionArray;
        }

        $transaction = new Transaction();
        $transaction->fromArray($transactionArray);
        return $transaction;
    }

    /**
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->table_id;
    }
}
