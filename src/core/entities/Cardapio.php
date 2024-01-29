<?php

namespace MsPedidosApp\core\entities;

use MsPedidosApp\entities\DateTime;

class Cardapio extends Entity
{
    public string $id;
    public string $nome;
    public string $descricao;
    public string $categoria;
    public float $valor;
    public DateTime|string|null $createdAt;
    public DateTime|string|null $updatedAt;

}
