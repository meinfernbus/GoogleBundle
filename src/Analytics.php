<?php

namespace AntiMattr\GoogleBundle;

use AntiMattr\GoogleBundle\Analytics\CustomVariable;
use AntiMattr\GoogleBundle\Analytics\Event;
use AntiMattr\GoogleBundle\Analytics\Item;
use AntiMattr\GoogleBundle\Analytics\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Analytics
{
    const EVENT_QUEUE_KEY = 'google_analytics/event/queue';
    const CUSTOM_PAGE_VIEW_KEY = 'google_analytics/page_view';
    const PAGE_VIEW_QUEUE_KEY = 'google_analytics/page_view/queue';
    const TRANSACTION_KEY = 'google_analytics/transaction';
    const ITEMS_KEY = 'google_analytics/items';

    private $customVariables = [];
    private $pageViewsWithBaseUrl = true;
    private $trackers;
    private $whitelist;
    private $api_key;
    private $client_id;
    private $table_id;

    /**
     * @see https://support.google.com/analytics/topic/6009743?hl=de&ref_topic=1007027
     *
     * @var string
     */
    private $userId;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        RequestStack $requestStack,
        SessionInterface  $session,
        array $trackers = [],
        array $whitelist = [],
        array $dashboard = []
    ) {
        $this->requestStack = $requestStack;
        $this->trackers = $trackers;
        $this->whitelist = $whitelist;
        $this->api_key = isset($dashboard['api_key']) ? $dashboard['api_key'] : '';
        $this->client_id = isset($dashboard['client_id']) ? $dashboard['client_id'] : '';
        $this->table_id = isset($dashboard['table_id']) ? $dashboard['table_id'] : '';
        $this->session = $session;
    }

    public function excludeBaseUrl()
    {
        $this->pageViewsWithBaseUrl = false;
    }

    public function includeBaseUrl()
    {
        $this->pageViewsWithBaseUrl = true;
    }

    private function isValidConfigKey($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            throw new \InvalidArgumentException(sprintf('There is no tracker configuration assigned with the key "%s".', $trackerKey));
        }

        return true;
    }

    private function setTrackerProperty($tracker, $property, $value)
    {
        if ($this->isValidConfigKey($tracker)) {
            $this->trackers[$tracker][$property] = $value;
        }
    }

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
     * @param string $trackerKey
     * @param bool   $allowAnchor
     */
    public function setAllowAnchor($trackerKey, $allowAnchor)
    {
        $this->setTrackerProperty($trackerKey, 'allowAnchor', $allowAnchor);
    }

    /**
     * @param string $trackerKey
     *
     * @return bool $allowAnchor (default:false)
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
     * @param bool   $allowHash
     */
    public function setAllowHash($trackerKey, $allowHash)
    {
        $this->setTrackerProperty($trackerKey, 'allowHash', $allowHash);
    }

    /**
     * @param string $trackerKey
     *
     * @return bool $allowHash (default:false)
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
     * @param bool   $allowLinker
     */
    public function setAllowLinker($trackerKey, $allowLinker)
    {
        $this->setTrackerProperty($trackerKey, 'allowLinker', $allowLinker);
    }

    /**
     * @param string $trackerKey
     *
     * @return bool $allowLinker (default:true)
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
     * @param bool   $includeNamePrefix
     */
    public function setIncludeNamePrefix($trackerKey, $includeNamePrefix)
    {
        $this->setTrackerProperty($trackerKey, 'includeNamePrefix', $includeNamePrefix);
    }

    /**
     * @param string $trackerKey
     *
     * @return bool $includeNamePrefix (default:true)
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
     * @param bool   $anonymizeIp
     */
    public function setAnonymizeIp($trackerKey, $anonymizeIp)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return;
        }
        $this->trackers[$trackerKey]['anonymizeIp'] = $anonymizeIp;
    }

    /**
     * @param string $trackerKey
     *
     * @return bool
     */
    public function getAnonymizeIp($trackerKey)
    {
        if (!array_key_exists($trackerKey, $this->trackers)) {
            return false;
        }
        $trackerConfig = $this->trackers[$trackerKey];
        if (!array_key_exists('anonymizeIp', $trackerConfig)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $trackerKey
     * @param bool   $name
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
     * @param int    $siteSpeedSampleRate
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

    public function getCustomPageView(): ?string
    {
        $customPageView = $this->session->get(self::CUSTOM_PAGE_VIEW_KEY);
        $this->session->remove(self::CUSTOM_PAGE_VIEW_KEY);

        return $customPageView;
    }

    /**
     * @return bool $hasCustomPageView
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
        $this->session->set(self::CUSTOM_PAGE_VIEW_KEY, $customPageView);
    }

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
     * @return bool $hasCustomVariables
     */
    public function hasCustomVariables()
    {
        if (!empty($this->customVariables)) {
            return true;
        }

        return false;
    }

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
     * @return bool $hasEventQueue
     */
    public function hasEventQueue()
    {
        return $this->has(self::EVENT_QUEUE_KEY);
    }

    public function addItem(Item $item)
    {
        $this->add(self::ITEMS_KEY, $item);
    }

    /**
     * @return bool $hasItems
     */
    public function hasItems()
    {
        return $this->has(self::ITEMS_KEY);
    }

    /**
     * @return bool $hasItem
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
        $this->session->set(self::ITEMS_KEY, $items);
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
     * @return bool $hasPageViewQueue
     */
    public function hasPageViewQueue()
    {
        return $this->has(self::PAGE_VIEW_QUEUE_KEY);
    }

    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
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
            $requestUri .= '?' . $query;
        }

        return $requestUri;
    }

    /**
     * @return array $trackers
     */
    public function getTrackers(array $trackers = [])
    {
        if (!empty($trackers)) {
            $trackers = [];
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
     * @return bool $isTransactionValid
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
        $this->session->remove(self::TRANSACTION_KEY);

        return $transaction;
    }

    /**
     * @return bool $hasTransaction
     */
    public function hasTransaction()
    {
        return $this->has(self::TRANSACTION_KEY);
    }

    public function setTransaction(Transaction $transaction)
    {
        $this->session->set(self::TRANSACTION_KEY, $transaction);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    private function add($key, $value)
    {
        $bucket = $this->session->get($key, []);
        $bucket[] = $value;
        $this->session->set($key, $bucket);
    }

    /**
     * @param string $key
     *
     * @return bool $hasKey
     */
    private function has($key)
    {
        if (!$this->session->isStarted()) {
            return false;
        }

        $bucket = $this->session->get($key, []);

        return !empty($bucket);
    }

    /**
     * @param string $key
     *
     * @return array $value
     */
    private function get($key)
    {
        return $this->session->get($key, []);
    }

    /**
     * @param string $key
     *
     * @return array $value
     */
    private function getOnce($key)
    {
        $value = $this->session->get($key, []);
        $this->session->remove($key);

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
        return $this->session->get(self::TRANSACTION_KEY);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
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

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function hasUserId()
    {
        return !empty($this->userId);
    }
}
