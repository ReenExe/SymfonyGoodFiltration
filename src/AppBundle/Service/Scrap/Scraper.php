<?php

namespace AppBundle\Service\Scrap;

use Doctrine\DBAL\Connection;

abstract class Scraper
{
    const END = 1;

    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

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