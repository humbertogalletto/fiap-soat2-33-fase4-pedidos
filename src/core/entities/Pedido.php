<?php

namespace MsPedidosApp\core\entities;


use MsPedidosApp\entities\DateTime;

class Pedido extends Entity
{

    public string $id;
    public DateTime|string $recebimento;
    public DateTime|string|null $fechamento;
    public DateTime|string|null $pagamento;
    public string $status;
    public array|null $itens;
    public array|null $preparo;

    public float $totalPedido;

    public DateTime|string|null $createdAt;
    public DateTime|string|null $updatedAt;

}
