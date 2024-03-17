<?php

namespace MsPedidosApp\core\interfaces;

use MsPedidosApp\core\entities\Pedido;

interface IPedidoService
{
    public function create(array $request): Pedido;
    public function update(string $id, array $request): Pedido;
    public function delete(string $id): array;
    public function show(string $id): Pedido;
    public function list(): array;
    public function statusList(): array;
    public function clearClients(array $clients): array;

}
