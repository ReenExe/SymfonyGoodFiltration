<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class CreateMediaSiteStructureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('create:media:site:structure');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $this->createProcess();

        $this->process(1);

        $duration = microtime(true) - $startTime;
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);
    }

    private function process($limit)
    {
        $pages = array_column($this->getPages($limit), 'path');

        foreach ($pages as $path) {
            $html = $this->getCachedPage($path);

            $data = $this->getPageData($html);

//            $this->updateProcess($path)
        }
    }

    private function getPageData($html)
    {
        $crawler = new Crawler($html);

        $data = [
            'title' => trim($crawler->filter('.b-tab-item__title-inner h1')->text())
        ];

        return $data;
    }

    private function getPages($limit)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        return $connection
            ->fetchAll("
                SELECT `path` FROM `media_site_structure_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ");
    }

    private function getCachedPage($path)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        return $connection
            ->fetchColumn("
                SELECT `value` FROM `media_site_page_cache`
                WHERE `path` = :path
            " ,compact('path'));

    }

    private function updateProcess($path)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        return $connection
            ->exec("
                UPDATE `media_site_structure_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }

    private function createProcess()
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_structure_queue` (
                `path` VARCHAR(255) PRIMARY KEY,
                `process` TINYINT DEFAULT 0
            )
              AS
            SELECT `path` FROM `media_site_page_queue`;
        ");
    }
}