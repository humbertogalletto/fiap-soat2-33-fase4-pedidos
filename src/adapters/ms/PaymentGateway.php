<?php

namespace MsPedidosApp\adapters\ms;

use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\ms\base\MsApi;
use RdKafka\Exception;

class PaymentGateway extends MsApi
{
    private static ?self $instance = null;

    /**
     * Factoring
     */
    private function __construct(){}

    public static function order($data): array
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
        }
        $paymentGateway = self::$instance;

        return $paymentGateway->orderByQueue($data);
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function orderByQueue(array $data): array
    {
        return json_decode($this->sendToQueue(json_encode($data), "{$_ENV['KAFKA_TOPIC_PEDIDO_NOVO']}"), true);
    }
}
