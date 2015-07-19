<?php

namespace AppBundle\Command;

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
       $output->writeln('<info>Execute</info>');
    }
}