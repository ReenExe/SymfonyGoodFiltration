<?php

namespace AppBundle\Command\MediaSite;

use AppBundle\Command\Core\QueueComand;

class AnalyzeStructureCommand extends QueueComand
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