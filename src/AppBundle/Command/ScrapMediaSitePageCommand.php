<?php

namespace AppBundle\Command;

use AppBundle\Command\Core\QueueComand;

class ScrapMediaSitePageCommand extends QueueComand
{
    protected function configure()
    {
        $this->setName('scrap:media:site:page');
    }

    protected function getService()
    {
        return $this->getContainer()->get('scrap_page_service');
    }
}