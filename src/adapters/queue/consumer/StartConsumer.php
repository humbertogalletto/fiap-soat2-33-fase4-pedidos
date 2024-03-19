<?php

use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\queue\QueueGateway;
use MsPedidosApp\core\exceptions\ServicesException;

try {
    QueueGateway::listen();
} catch (
    GuzzleException|
    \MongoDB\Driver\Exception\Exception|
    ServicesException|
    \RdKafka\Exception $e
){
    echo "Não é possível escutar a fila {$e->getMessage()}";
}
