<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParseMediaSitePageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('scrap:media:site:page');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $this->createCache();

        $this->process(10);

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
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://fs.to']);

        $pages = array_column($this->getPages($limit), 'path');

        foreach ($pages as $path) {
            $html = $client->get($path)->getBody()->getContents();

            $this->savePageCache($path, $html);

            $this->updateProcess($path);
        }
    }

    private function createCache()
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_page_cache` (
                `path` VARCHAR(255) PRIMARY KEY,
                `value` MEDIUMBLOB
            );
        ");
    }

    private function getPages($limit)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        return $connection
            ->fetchAll("
                SELECT `path` FROM `media_site_page_queue`
                WHERE `process` = 0
                LIMIT $limit;
            ");
    }

    private function updateProcess($path)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        return $connection
            ->exec("
                UPDATE `media_site_page_queue`
                SET `process` = 1
                WHERE `path` = '$path'
            ");
    }

    private function savePageCache($path, $value)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();
        $connection->insert('media_site_page_cache', compact('path', 'value'));
    }
}