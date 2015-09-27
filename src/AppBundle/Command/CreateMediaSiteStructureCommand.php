<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMediaSiteStructureCommand extends AbstractQueueComand
{
    protected $limit = 1000;

    protected function configure()
    {
        $this->setName('create:media:site:structure');
    }

    protected function getService()
    {
        return $this->getContainer()->get('mesi.structure_service');
    }
}