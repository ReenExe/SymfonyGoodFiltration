<?php

namespace AppBundle\Service\Scrap;

use AppBundle\Service\AbstractQueueService;

abstract class Scraper extends AbstractQueueService
{
    /**
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new \GuzzleHttp\Client([
            'base_uri'      => 'http://fs.to'
        ]);
    }
}