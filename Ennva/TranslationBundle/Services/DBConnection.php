<?php

namespace Cineca\TranslationBundle\Services;

use Doctrine\DBAL\Connection;

/**
*
*/
class DBConnection
{
    private $connection;

    public function __construct(Connection $dbalConnection)
    {
        $this->connection = $dbalConnection;
    }
}