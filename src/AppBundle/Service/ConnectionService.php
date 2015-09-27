<?php

namespace AppBundle\Service;

use Doctrine\DBAL\Connection;

abstract class ConnectionService
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}