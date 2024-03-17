<?php

namespace MsPedidosApp\controllers;

use MsPedidosApp\adapters\ui\CreateAdapter;
use MsPedidosApp\adapters\ui\DeleteAdapter;
use MsPedidosApp\adapters\ui\ListAdapter;
use MsPedidosApp\adapters\ui\ShowAdapter;
use MsPedidosApp\adapters\ui\UpdateAdapter;
use MsPedidosApp\core\interfaces\IClienteService;

class ClienteController
{
    public IClienteService $service;

    /**
     * @param IClienteService $service
     */
    public function __construct(IClienteService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $request
     * @return bool|string
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
     * @return bool|string
     */
    public function delete($id): bool|string
    {
        $this->service->delete($id);
        return DeleteAdapter::json();
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function show($id): bool|string
    {
        return ShowAdapter::json($this->service->show($id));
    }

    /**
     * @return bool|string
     */
    public function list(): bool|string
    {
        return ListAdapter::json($this->service->list());
    }

    /**
     * @param string $cpf
     * @return bool|string
     */
    public function showByCpf(string $cpf): bool|string
    {
        return ShowAdapter::json($this->service->showByCpf($cpf));
    }

}
