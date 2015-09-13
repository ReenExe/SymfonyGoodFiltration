<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ScrapMediaSiteCommand extends ContainerAwareCommand
{
    protected $limit = 100;
    /**
     * @return \AppBundle\Service\Scrap\Scraper
     */
    abstract protected function getService();

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $exitCode = $this->getService()->execute($this->limit);

        $duration = microtime(true) - $startTime;
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);

        return $exitCode;
    }
}