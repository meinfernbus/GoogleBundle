<?php

namespace AntiMattr\GoogleBundle\Helper;

use AntiMattr\GoogleBundle\Analytics;
use AntiMattr\GoogleBundle\Analytics\Event;
use Symfony\Component\Templating\Helper\Helper;

class AnalyticsHelper extends Helper
{
    private $analytics;
    private $sourceHttps;
    private $sourceHttp;
    private $sourceEndpoint;

    public function __construct(Analytics $analytics, $sourceHttps, $sourceHttp, $sourceEndpoint)
    {
        $this->analytics = $analytics;
        $this->sourceHttps = $sourceHttps;
        $this->sourceHttp = $sourceHttp;
        $this->sourceEndpoint = $sourceEndpoint;
    }

    public function getAllowAnchor($trackerKey)
    {
        return $this->analytics->getAllowAnchor($trackerKey);
    }

    public function getAllowHash($trackerKey)
    {
        return $this->analytics->getAllowHash($trackerKey);
    }

    public function getAllowLinker($trackerKey)
    {
        return $this->analytics->getAllowLinker($trackerKey);
    }

    public function isEnhancedEcommerce()
    {
        return $this->analytics->isEnhancedEcommerce();
    }

    public function getTrackerName($trackerKey)
    {
        if ($this->analytics->getIncludeNamePrefix($trackerKey)) {
            return $this->analytics->getTrackerName($trackerKey).'.';
        }
        return "";
    }

    public function getSiteSpeedSampleRate($trackerKey)
    {
        return $this->analytics->getSiteSpeedSampleRate($trackerKey);
    }

    public function hasCustomPageView()
    {
        return $this->analytics->hasCustomPageView();
    }

    public function getCustomPageView()
    {
        return $this->analytics->getCustomPageView();
    }

    public function hasCustomVariables()
    {
        return $this->analytics->hasCustomVariables();
    }

    public function getCustomVariables()
    {
        return $this->analytics->getCustomVariables();
    }

    public function hasEventQueue()
    {
        return $this->analytics->hasEventQueue();
    }

    public function getEventQueue()
    {
        return $this->analytics->getEventQueue();
    }

    public function hasImpressions($action = '')
    {
        return $this->analytics->hasImpressions($action);
    }

    public function getImpressions($action = '')
    {
        return $this->analytics->getImpressions($action);
    }

    public function hasItems()
    {
        return $this->analytics->hasItems();
    }

    public function getItems()
    {
        return $this->analytics->getItems();
    }

    public function getRequestUri()
    {
        return $this->analytics->getRequestUri();
    }

    public function hasPageViewQueue()
    {
        return $this->analytics->hasPageViewQueue();
    }

    public function getPageViewQueue()
    {
        return $this->analytics->getPageViewQueue();
    }

    public function getPlugins($trackerKey)
    {
        return $this->analytics->getPlugins($trackerKey);
    }

    public function hasProducts($action = '')
    {
        return $this->analytics->hasProducts($action);
    }

    public function getProducts($action = '')
    {
        return $this->analytics->getProducts($action);
    }

    public function getSourceHttps()
    {
        return $this->sourceHttps;
    }

    public function getSourceHttp()
    {
        return $this->sourceHttp;
    }

    public function getSourceEndpoint()
    {
        return $this->sourceEndpoint;
    }

    public function getTrackers(array $trackers = array())
    {
        return $this->analytics->getTrackers($trackers);
    }
    
    public function getApiKey()
    {
        return $this->analytics->getApiKey();
    }
    
    public function getClientId()
    {
        return $this->analytics->getClientId();
    }
    
    public function getTableId()
    {
        return $this->analytics->getTableId();
    }

    public function isTransactionValid()
    {
        return $this->analytics->isTransactionValid();
    }

    public function getTransaction()
    {
        return $this->analytics->getTransaction();
    }

    public function getName()
    {
        return 'google_analytics';
    }
}
