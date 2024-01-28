<?php

namespace MsPedidosApp\core\entities;


use MsPedidosApp\entities\DateTime;

class Pedido extends Entity
{

    public string $_id;
    public DateTime|string $recebimento;
    public DateTime|string|null $fechamento;
    public DateTime|string|null $pagamento;
    public string $status;
    public array|null $itens;
    public array|null $preparo;

    public float $valor_total;

    public DateTime|string|null $created_at;
    public DateTime|string|null $updated_at;


}