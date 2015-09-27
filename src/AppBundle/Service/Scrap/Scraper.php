<?php

namespace AppBundle\Service\Scrap;

use AppBundle\Service\ConnectionService;

abstract class Scraper extends ConnectionService
{
    const END = 1;

    /**
     * @param $limit
     * @return mixed
     */
    public function execute($limit)
    {
        $this->createCache();
        return $this->process($limit);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new \GuzzleHttp\Client([
            'base_uri'      => 'http://fs.to'
        ]);
    }

    abstract protected function createCache();

    abstract protected function process($limit);
}