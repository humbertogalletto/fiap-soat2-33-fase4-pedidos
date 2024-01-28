<?php

namespace MsPedidosApp\adapters\db\external\base;

use MsPedidosApp\external\base\Connect;

abstract class ConnectionData
{

    public function getMongoDatabase(): string
    {
        return $_ENV['MONGO_DATABASE'];
    }

    public function getMongoPort(): string
    {
        return $_ENV['MONGO_PORT'];
    }

    public function getMongoHost():string
    {
        return $_ENV['MONGO_HOST'];
    }

    public function getMongoUsername():string
    {
        return $_ENV['MONGO_USERNAME'];
    }

    public function getMongoPassword():string
    {
        return $_ENV['MONGO_PASSWORD'];
    }

}