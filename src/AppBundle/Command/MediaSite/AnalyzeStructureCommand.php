<?php

namespace AppBundle\Command\MediaSite;

use AppBundle\Command\AbstractQueueComand;

class AnalyzeStructureCommand extends AbstractQueueComand
{
    protected function configure()
    {
        $this->setName('media:site:analyze:structure');
    }

    protected function getService()
    {
        return $this->getContainer()->get('mesi.analyze_structure_service');
    }
}