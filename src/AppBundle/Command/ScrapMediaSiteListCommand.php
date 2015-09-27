<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class ScrapMediaSiteListCommand extends QueueComand
{
    protected function configure()
    {
        $this->setName('scrap:media:site:list');
    }

    protected function getService()
    {
        return $this->getContainer()->get('scrap_list_service');
    }
}