<?php

namespace MsPedidosApp\core\interfaces;

use MsPedidosApp\core\entities\Cardapio;

interface ICardapioService
{
    public function create(array $request): Cardapio;
    public function update(string $id, array $request): Cardapio;
    public function delete(string $id): array;
    public function show(string $id): Cardapio;
    public function list(): array;
    public function categoriasList(): array;

}
