<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Analytics\CustomVariable;
use AntiMattr\GoogleBundle\Analytics\Event;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Transaction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Analytics
{
    const EVENT_QUEUE_KEY      = 'google_analytics/event/queue';
    const CUSTOM_PAGE_VIEW_KEY = 'google_analytics/page_view';
    const PAGE_VIEW_QUEUE_KEY  = 'google_analytics/page_view/queue';
    const TRANSACTION_KEY      = 'google_analytics/transaction';
    const ITEMS_KEY            = 'google_analytics/items';

    private $container;
    private $customVariables = array();
    private $pageViewsWithBaseUrl = true;
    private $trackers;

    public function __construct(ContainerInterface $container, array $trackers = array())
    {
        $this->container = $container;
        $this->trackers = $trackers;
    }

    public function excludeBaseUrl()
    {
        $this->pageViewsWithBaseUrl = false;
    }

    public function includeBaseUrl()
    {
        $this->pageViewsWithBaseUrl = true;
    }

    /**
     * @param string $trackerKey
     * @param boolean $allowHash
     */
    public function setAllowHash($trackerKey, $allowHash)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return;
        }
        $this->trackers[$trackerKey]['allowHash'] = $allowHash;
    }

    /**
     * @param string $trackerKey
     * @return boolean $allowHash
     */
    public function getAllowHash($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return false;
        }
        $trackerConfig = $this->trackers[$trackerKey];
        if (!array_key_exists('allowHash', $trackerConfig)) {
            return false;
        }
        return $trackerConfig['allowHash'];
    }

    /**
     * @param string $trackerKey
     * @param boolean $allowLinker
     */
    public function setAllowLinker($trackerKey, $allowLinker)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return;
        }
        $this->trackers[$trackerKey]['allowLinker'] = $allowLinker;
    }

    /**
     * @param string $trackerKey
     * @return boolean $allowLinker
     */
    public function getAllowLinker($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return true;
        }
        $trackerConfig = $this->trackers[$trackerKey];
        if (!array_key_exists('allowLinker', $trackerConfig)) {
            return true;
        }
        return $trackerConfig['allowLinker'];
    }

    /**
     * @param string $trackerKey
     * @param boolean $trackPageLoadTime
     */
    public function setTrackPageLoadTime($trackerKey, $trackPageLoadTime)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return;
        }
        $this->trackers[$trackerKey]['trackPageLoadTime'] = $trackPageLoadTime;
    }

    /**
     * @param string $trackerKey
     * @return boolean $trackPageLoadTime
     */
    public function getTrackPageLoadTime($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return false;
        }
        $trackerConfig = $this->trackers[$trackerKey];
        if (!array_key_exists('trackPageLoadTime', $trackerConfig)) {
            return false;
        }
        return $trackerConfig['trackPageLoadTime'];
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
     * @param CustomVariable $customVariable
     */
    public function addCustomVariable(CustomVariable $customVariable)
    {
        $this->customVariables[] = $customVariable;
    }

    /**
     * @return array $customVariables
     */
    public function getCustomVariables()
    {
        return $this->customVariables;
    }

    /**
     * @return boolean $hasCustomVariables
     */
    public function hasCustomVariables()
    {
        if (!empty($this->customVariables)) {
            return true;
        }
        return false;
    }

    /**
     * @param Event $event
     */
    public function enqueueEvent(Event $event)
    {
        $this->add(self::EVENT_QUEUE_KEY, $event);
    }

    /**
     * @param array $eventQueue
     */
    public function getEventQueue()
    {
        return $this->getOnce(self::EVENT_QUEUE_KEY);
    }

    /**
     * @return boolean $hasEventQueue
     */
    public function hasEventQueue()
    {
        return $this->has(self::EVENT_QUEUE_KEY);
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->add(self::ITEMS_KEY, $item);
    }

    /**
     * @return boolean $hasItems
     */
    public function hasItems()
    {
        return $this->has(self::ITEMS_KEY);
    }

    /**
     * @param Item $item
     * @return boolean $hasItem
     */
    public function hasItem(Item $item)
    {
        if (!$this->hasItems()) {
            return false;
        }
        $items = $this->getItemsFromSession();
        return in_array($item, $items, true);
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->container->get('session')->set(self::ITEMS_KEY, $items);
    }

    public function getItems()
    {
        return $this->getOnce(self::ITEMS_KEY);
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
     * @return Symfony\Component\HttpFoundation\Request $request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * If Custom Page view not set,
     * Then requestUri is used as an alternative
     *
     * @return string $requestUri
     */
    public function getRequestUri()
    {
        $request = $this->getRequest();
        $requestUri = $request->getRequestUri();
        if (!$this->pageViewsWithBaseUrl) {
            $baseUrl = $request->getBaseUrl();
            if ($baseUrl != '/') {
                return str_replace($baseUrl, '', $requestUri);
            }
            return $requestUri;
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
        if (!$this->hasTransaction() || (null === $this->getTransactionFromSession()->getOrderNumber())) {
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
        $this->container->get('session')->set(self::TRANSACTION_KEY, $transaction);
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
     * @return boolean $hasKey
     */
    private function has($key)
    {
        $bucket = $this->container->get('session')->get($key, array());
        return !empty($bucket);
    }

    /**
     * @param string $key
     * @return array $value
     */
    private function get($key)
    {
        return $this->container->get('session')->get($key, array());
    }

    /**
     * @param string $key
     * @return array $value
     */
    private function getOnce($key)
    {
        $value = $this->container->get('session')->get($key, array());
        $this->container->get('session')->remove($key);
        return $value;
    }

    /**
     * @return array $items
     */
    private function getItemsFromSession()
    {
        return $this->get(self::ITEMS_KEY);
    }

    /**
     * @return Transaction $transaction
     */
    private function getTransactionFromSession()
    {
        return $this->container->get('session')->get(self::TRANSACTION_KEY);
    }
}
