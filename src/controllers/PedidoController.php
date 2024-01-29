<?php

namespace MsPedidosApp\controllers;

use MsPedidosApp\adapters\ui\CreateAdapter;
use MsPedidosApp\adapters\ui\DeleteAdapter;
use MsPedidosApp\adapters\ui\ListAdapter;
use MsPedidosApp\adapters\ui\ShowAdapter;
use MsPedidosApp\adapters\ui\UpdateAdapter;
use MsPedidosApp\core\interfaces\IPedidoService;


class PedidoController
{
    /**
     * @var IPedidoService
     */
    public IPedidoService $service;

    /**
     * @param IPedidoService $service
     */
    public function __construct(IPedidoService $service)
    {
        $this->service = $service;
    }


    /**
     * @param $request
     * @return false|string
     */
    public function create($request): bool|string
    {
        return CreateAdapter::json($this->service->create($request));
    }

    /**
     * @param $id
     * @param $request
     * @return bool|string
     */
    public function update($id, $request): bool|string
    {
        return UpdateAdapter::json($this->service->update($id, $request));
    }

    /**
     * @param $id
     * @return false|string
     */
    public function delete($id): bool|string
    {
        $this->service->delete($id);
        return DeleteAdapter::json();
    }

    /**
     * @param $id
     * @return false|string
     */
    public function show($id): bool|string
    {
        return ShowAdapter::json($this->service->show($id));
    }

    /**
     * @return false|string
     */
    public function list(): bool|string
    {
        return ListAdapter::json($this->service->list());
    }

    /**
     * @return false|string
     */
    public function statusList(): bool|string
    {
        return ListAdapter::json($this->service->statusList());
    }

    /**
     * @return false|string
     */
    public function pedidos(): bool|string
    {
        return ListAdapter::json($this->service->pedidos());
    }

    /**
     * @param $status
     * @return false|string
     */
    public function listByStatus($status): bool|string
    {
        return ListAdapter::json($this->service->listByStatus($status));
    }
}
