<?php

namespace MsPedidosApp\core\interfaces;

use MsPedidosApp\core\entities\Cliente;

interface IClienteService
{
    public function create(array $request): Cliente | null;
    public function update(string $id, array $request): Cliente;
    public function delete(string $id): array;
    public function show(string $id): Cliente;
    public function list(): array;
    public function showByCpf(string $cpf): Cliente;

}
