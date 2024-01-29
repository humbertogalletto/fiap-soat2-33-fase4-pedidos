<?php

namespace MsPedidosApp\core\interfaces;

use MsPedidosApp\adapters\db\external\MongoRepository;

interface IDBGateway
{

    public function getRepository(): MongoRepository;

    public function setRepository(MongoRepository $mongoMongoRepository): void;

}
