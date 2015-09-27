<?php

namespace AppBundle\Service;

abstract class AbstractQueueService extends ConnectionService
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

    abstract protected function createCache();

    abstract protected function process($limit);
}