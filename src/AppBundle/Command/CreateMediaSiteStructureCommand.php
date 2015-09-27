<?php

namespace AppBundle\Command;

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