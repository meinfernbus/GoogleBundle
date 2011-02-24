<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Analytics\CustomVariable;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Transaction;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Analytics
{
    const CUSTOM_PAGE_VIEW_KEY = 'google_analytics/page_view';
    const PAGE_VIEW_QUEUE_KEY  = 'google_analytics/page_view/queue';

    private $container;
    private $customVariables = array();
    private $items = array();
    private $pageViewsWithBaseUrl = true;
    private $trackers;
    private $transaction;

    public function __construct(ContainerInterface $container, array $trackers = array())
    {
        $this->container = $container;
        $this->trackers = $trackers;
    }

    /**
     * @param string $trackerKey
     * @return boolean $allowLinker
     */
    public function getAllowLinker($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return 'true';
        }
        $trackerConfig = $this->trackers[$trackerKey];
        if (!array_key_exists('allowLinker', $trackerConfig)) {
            return 'true';
        }
        return (string) $trackerConfig['allowLinker'];
    }

    /**
     * @param string $trackerKey
     * @return boolean $allowHash
     */
    public function getAllowHash($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return 'false';
        }
        $trackerConfig = $this->trackers[$trackerKey];
        if (!array_key_exists('allowLinker', $trackerConfig)) {
            return 'false';
        }
        return (string) $trackerConfig['allowLinker'];
    }

    /**
     * @return string $customPageView
     */
    public function getCustomPageView()
    {
        return $this->get(self::CUSTOM_PAGE_VIEW_KEY);
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
        if (!empty($this->customVars)) {
            return true;
        }
        return false;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return boolean $hasItems
     */
    public function hasItems()
    {
        if (!empty($this->items)) {
            return true;
        }
        return false;
    }

    /**
     * @param Item $item
     * @return boolean $hasItem
     */
    public function hasItem(Item $item)
    {
        if ($this->items instanceof Collection) {
            return $this->items->contains($item);
        } else {
            return in_array($item, $this->items, true);
        }
    }

    /**
     * @param Item $item
     * @return Item $item | null
     */
    public function removeItem(Item $item)
    {
        if (!$this->hasItem($item)) {
            return null;
        }
        if ($this->items instanceof Collection) {
            return $this->items->removeElement($item);
        } else {
            unset($this->items[array_search($item, $this->items, true)]);
            return $item;
        }
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
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
        return $this->get(self::PAGE_VIEW_QUEUE_KEY);
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
        if (!$this->transaction || !$this->transaction->getOrderNumber()) {
            return false;
        }
        if ($this->hasItems()) {
            foreach ($this->items as $item) {
                if (!$item->getOrderNumber() || !$item->getSku() || !$item->getPrice() || !$item->getQuantity()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param Transaction $transaction
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return Transaction $transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
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
        $value = $this->container->get('session')->get($key, array());
        $this->container->get('session')->remove($key);
        return $value;
    }
}
