<?php

namespace MsPedidosApp\controllers;

use MsPedidosApp\core\interfaces\IPagamentoService;

class PagamentoController
{
    public IPagamentoService $service;

    public function __construct(IPagamentoService $service)
    {
        $this->service = $service;
    }

    public function process(array $data)
    {
        $this->service->process($data);
    }


}