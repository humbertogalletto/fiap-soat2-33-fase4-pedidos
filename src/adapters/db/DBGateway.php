<?php

namespace MsPedidosApp\adapters\db;

use MsPedidosApp\adapters\db\external\MongoConnection;
use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\external\MongoRedisRepository;
use MsPedidosApp\external\RedisConnection;
use MsPedidosApp\external\RedisRepository;

class DBGateway
{
    public static function getRepository(): MongoRepository
    {

        $connection = MongoConnection::getInstance();
        $repository = new MongoRepository($connection);
        $repository->setDb($connection->getMongoDatabase());

        return $repository;

    }
}
