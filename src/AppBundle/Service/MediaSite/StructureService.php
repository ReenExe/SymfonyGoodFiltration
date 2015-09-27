<?php

namespace AppBundle\Service\MediaSite;

use AppBundle\Service\AbstractQueueService;
use Symfony\Component\DomCrawler\Crawler;

class StructureService extends AbstractQueueService
{
    public function clear()
    {
        $this->connection->exec('
            DROP TABLE IF EXISTS `media_site_structure_queue`;
            DROP TABLE IF EXISTS `media_site_structure`;
        ');
    }

    protected function process($limit)
    {
        $pages = array_column($this->getPages($limit), 'path');

        if (empty($pages)) {
            return self::END;
        }

        foreach ($pages as $path) {
            $html = $this->getCachedPage($path);

            $data = $this->getPageData($html);

            $this->savePageData($path, $data);

            $this->updateProcess($path);
        }
    }

    private function getPageData($html)
    {
        $crawler = new Crawler($html);

        $data = [
            'title' => trim($crawler->filter('.b-tab-item__title-inner h1')->text()),
            'description' => trim($crawler->filter('.b-tab-item__description')->text()),
            // background-image: url(<image>);
            'image' => substr($crawler->filter('.images-show')->attr('style'), 22, -2),
            'tags' => json_encode($this->getPageTags($crawler)),
        ];

        return $data;
    }

    private function getPageTags(Crawler $crawler)
    {
        return $crawler
            ->filter('.item-info table tr')
            ->each(function (Crawler $crawler) {
                $name = trim($crawler->filter('td')->first()->text());

                $list = $crawler->filter('.tag span')->each(function (Crawler $crawler) {
                    return $crawler->text();
                });

                return compact('name', 'list');
            });
    }


    private function getPages($limit)
    {
        return $this->connection
            ->fetchAll("
                SELECT `path` FROM `media_site_structure_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ");
    }

    private function getCachedPage($path)
    {
        return $this->connection
            ->fetchColumn("
                SELECT `value` FROM `media_site_page_cache`
                WHERE `path` = :path
            " ,compact('path'));

    }

    private function updateProcess($path)
    {
        return $this->connection
            ->exec("
                UPDATE `media_site_structure_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }

    private function savePageData($path, array $data)
    {
        $data['path'] = $path;
        $this->connection->insert('media_site_structure', $data);
    }

    protected function createCache()
    {
        $this->createProcess();
    }

    protected function createProcess()
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_structure_queue` (
                `path` VARCHAR(255) PRIMARY KEY,
                `process` TINYINT DEFAULT 0
            )
              AS
            SELECT `path` FROM `media_site_page_queue`;
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_structure`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `path` VARCHAR(255),
                `title` TEXT,
                `description` TEXT,
                `image` TEXT,
                `tags` TEXT
            );
        ");
    }
}