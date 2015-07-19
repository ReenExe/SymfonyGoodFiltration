<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParseMediaSiteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('parse:media:site');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $connection->executeQuery("
            CREATE TABLE IF NOT EXISTS `site_page_cache` (
                `url` VARCHAR(255) PRIMARY KEY,
                `value` TEXT
            );
        ");

        $this->parse('http://fs.to/texts/other/');

        $output->writeln('<info>Execute</info>');
    }

    private function parse($url)
    {
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
            INSERT INTO `site_page_cache` (`url`, `value`)
            VALUES (:url, :value)
        ", compact('url', 'value'));

        return $value;
    }
}