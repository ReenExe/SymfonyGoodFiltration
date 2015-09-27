<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class CreateMediaSiteStructureCommand extends QueueComand
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