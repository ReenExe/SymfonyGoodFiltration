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

        $crawler = new Crawler($this->fetchPage('/texts/other/'));

        $details = $crawler->filter('.b-poster-detail');

        $count = $details->count();

        $links = $details->filter('.b-poster-detail__link')->each(function (Crawler $crawler) {
            return $crawler->attr('href');
        });

        foreach ($links as $link) {
            $this->fetchPage($link);
        }

        $duration = microtime(true) - $startTime;

        $output->writeln([
            "<info>Count: $count</info>",
            "<info>Execute: $duration</info>",
        ]);
    }

    private function fetchPage($path)
    {
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

        $value = file_get_contents($url);

        $connection->executeQuery("
            REPLACE INTO `site_page_cache` (`url`, `value`)
            VALUES (:url, :value)
        ", compact('url', 'value'));

        return $value;
    }
}