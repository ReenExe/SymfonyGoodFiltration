<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ParseMediaSiteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('parse:media:site');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        // TODO: need rewrite as save better for file storage
        $connection->executeQuery("
            -- DROP TABLE `site_page_cache`;
            CREATE TABLE IF NOT EXISTS `site_page_cache` (
                `url` VARCHAR(255) PRIMARY KEY,
                `value` MEDIUMBLOB
            );
        ");

        $this->recursiveParseListPage('/texts/other/');

        $duration = microtime(true) - $startTime;

        $output->writeln([
            "<info>Execute: $duration</info>",
        ]);
    }

    private function recursiveParseListPage($pageUrl)
    {
        if (empty($html = $this->fetchPage($pageUrl))) return;

        $crawler = new Crawler($html);

        $nextLinkCrawler = $crawler->filter('.b-pager a.next-link');

        if ($nextLinkCrawler->count()) {
            $this->recursiveParseListPage($nextLinkCrawler->attr('href'));
        }
    }

    private function getDetailPageLinkCollection(Crawler $crawler)
    {
        return $crawler
            ->filter('.b-poster-detail .b-poster-detail__link')
            ->each(function (Crawler $crawler) {
                return $crawler->attr('href');
            });
    }

    private function fetchPage($path, $force = false)
    {
        static $count = 1;

        if ($count > 100) return;
        $count++;

        $url = "http://fs.to$path";

        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $stm = $connection->executeQuery("
            SELECT `value` FROM `site_page_cache`
            WHERE `url` = :url
        ", compact('url'));

        if ($value = $stm->fetchColumn()) {
            return $value;
        }

        if ($force === false) return;

        $value = file_get_contents($url);

        $connection->executeQuery("
            REPLACE INTO `site_page_cache` (`url`, `value`)
            VALUES (:url, :value)
        ", compact('url', 'value'));

        return $value;
    }
}