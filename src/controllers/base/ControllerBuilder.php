<?php

namespace MsPedidosApp\controllers\base;

use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\core\interfaces\IDBGateway;

abstract class ControllerBuilder implements IDBGateway
{
    protected array $controllers = [];

    public MongoRepository $repository;

    public function getRepository(): MongoRepository
    {
        return $this->repository;
    }

    public function setRepository(MongoRepository $mongoRepository): void
    {
        $this->repository = $mongoRepository;
    }

    protected function buildControllers($controller)
    {
        $parts = preg_split('/(?=[A-Z])/',array_reverse(explode("\\", $controller))[0]);
        array_pop($parts);

        $name = rtrim(implode("",$parts),"\\");

        if(!array_key_exists($controller, $this->controllers)){
            $service = new ('\MsPedidosApp\core\services\\'.$name.'Service')($this->repository);
            $this->controllers[$controller] = new $controller($service);
        }

        return $this->controllers[$controller];
    }
}
