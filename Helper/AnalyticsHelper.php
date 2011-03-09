<?php

namespace AntiMattr\GoogleBundle\Helper;

use AntiMattr\GoogleBundle\Analytics;
use Symfony\Component\Templating\Helper\Helper;

class AnalyticsHelper extends Helper
{
    private $analytics;

    public function __construct(Analytics $analytics)
    {
        $this->analytics = $analytics;
    }

    public function getAllowHash($trackerKey)
    {
        return $this->analytics->getAllowHash($trackerKey);
    }

    public function getAllowLinker($trackerKey)
    {
        return $this->analytics->getAllowLinker($trackerKey);
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

    public function getTrackers(array $trackers = array())
    {
        return $this->analytics->getTrackers($trackers);
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
