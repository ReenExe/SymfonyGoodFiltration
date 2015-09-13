<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParseMediaSiteListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('scrap:media:site:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $exitCode = $this->getContainer()->get('scrap_list_service')->execute(100);

        $duration = microtime(true) - $startTime;

        $output->writeln("<info>Execute: $duration</info>");

        return $exitCode;
    }
}