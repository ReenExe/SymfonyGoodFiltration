<?php

namespace AppBundle\Service\MediaSite;

use AppBundle\Service\ConnectionService;

class StructureService extends ConnectionService
{
    public function clear()
    {
        $this->connection->exec('
            DROP TABLE IF EXISTS `media_site_structure_queue`;
            DROP TABLE IF EXISTS `media_site_structure`;
        ');
    }
}