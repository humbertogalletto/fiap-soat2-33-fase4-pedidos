<?php

namespace MsPedidosApp\adapters\db\external;

use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use MongoDB\Driver\ServerApi;
use MsPedidosApp\adapters\db\external\base\ConnectionData;


class MongoConnection extends ConnectionData
{

    private static ?self $instance = null;

    public Manager $manager;

    // construct private para impedir que esta classe seja instanciada com new
    private function __construct(){}

    /**
     * @return void
     */
    private function connect(): void
    {
        try {
            $uriConnection = "mongodb://{$this->getMongoUsername()}:{$this->getMongoPassword()}@";
            $uriConnection .= "{$this->getMongoHost()}:{$this->getMongoPort()}/";
            $uriConnection .= "{$this->getMongoDatabase()}?authSource=admin";

            $v1 = new ServerApi(ServerApi::V1);
            $this->manager = new Manager($uriConnection, [], ['serverApi' => $v1]);
        } catch (\Exception $e) {
            echo "----> ", "\n";
            echo $e->getMessage(), "\n";
            exit;
        }
    }


    public static function getInstance(): self
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
            self::$instance->connect();
        }
        return self::$instance;
    }
}
