<?php

namespace MsPedidosApp\core\interfaces;

use MsPedidosApp\core\entities\Pedido;

interface IPagamentoService
{
    public function order(Pedido $pedido): array;
    public function process(array $data): bool;
    public function confirm(): bool;

}