<?php

namespace AppBundle\Command;

class ScrapMediaSitePageCommand extends ScrapMediaSiteCommand
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