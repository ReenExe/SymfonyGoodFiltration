<?php

namespace AppBundle\Service\Scrap;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class PageScraper extends Scraper
{
    protected function process($limit)
    {
        $pages = array_column($this->getPages($limit), 'path');

        if (empty($pages)) {
            return self::END;
        }

        /* @var $promises PromiseInterface[] */
        $promises = [];
        $client = $this->getClient();
        foreach ($pages as $path) {
            $promises[] = $client
                ->getAsync($path)
                ->then(function (ResponseInterface $response) use ($path) {
                    $html = $response->getBody()->getContents();
                    $this->savePageCache($path, $html);
                    $this->updateProcess($path);
                });
        }

        do {
            foreach ($promises as $key => $promise) {
                if ($promise->getState() === PromiseInterface::FULFILLED) {
                    unset($promises[$key]);
                } else {
                    $promise->wait();
                }
            }
        } while ($promises);
    }

    protected function createCache()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_page_cache` (
                `path` VARCHAR(255) PRIMARY KEY,
                `value` MEDIUMBLOB
            );
        ");
    }

    private function getPages($limit)
    {
        return $this->connection
            ->fetchAll("
                SELECT `path` FROM `media_site_page_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ");
    }

    private function updateProcess($path)
    {
        return $this->connection
            ->exec("
                UPDATE `media_site_page_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }

    private function savePageCache($path, $value)
    {
        $this->connection->insert('media_site_page_cache', compact('path', 'value'));
    }
}