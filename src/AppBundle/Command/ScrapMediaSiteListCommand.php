<?php

namespace AppBundle\Command;

class ScrapMediaSiteListCommand extends ScrapMediaSiteCommand
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