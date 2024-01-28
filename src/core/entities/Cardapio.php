<?php

namespace MsPedidosApp\core\entities;

use MsPedidosApp\entities\DateTime;

class Cardapio extends Entity
{
    public string $_id;
    public string $nome;
    public string $descricao;
    public string $categoria;
    public float $valor;
    public DateTime|string|null $created_at;
    public DateTime|string|null $updated_at;

}