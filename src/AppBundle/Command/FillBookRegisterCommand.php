<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FillBookRegisterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('media:site:fill:book');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $connection->exec('
            TRUNCATE TABLE `rr_book`;
        ');

        $connection->exec('
            INSERT INTO `rr_book` (`title`, `description`, `image`)
            SELECT `title`, `description`, `image` FROM `media_site_structure`;
        ');
    }
}