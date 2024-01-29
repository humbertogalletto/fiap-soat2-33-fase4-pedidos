<?php

namespace MsPedidosApp\controllers;

use MsPedidosApp\adapters\ui\CreateAdapter;
use MsPedidosApp\adapters\ui\DeleteAdapter;
use MsPedidosApp\adapters\ui\ListAdapter;
use MsPedidosApp\adapters\ui\ShowAdapter;
use MsPedidosApp\adapters\ui\UpdateAdapter;
use MsPedidosApp\core\interfaces\ICardapioService;

class CardapioController
{
    public ICardapioService $service;

    public function __construct(ICardapioService $service)
    {
        $this->service = $service;
    }


    public function create($request): bool|string
    {
        return CreateAdapter::json($this->service->create($request));
    }

    public function update($id, $request): bool|string
    {
        return UpdateAdapter::json($this->service->update($id, $request));
    }

    public function delete($id): bool|string
    {
        $this->service->delete($id);
        return DeleteAdapter::json();
    }

    public function show($id): bool|string
    {
        return ShowAdapter::json($this->service->show($id));
    }

    public function list(): bool|string
    {
        return ListAdapter::json($this->service->list());
    }

    public function categoriasList(): bool|string
    {
        return ListAdapter::json($this->service->categoriasList());
    }

}
