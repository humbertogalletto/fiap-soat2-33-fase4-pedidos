<?php

namespace MsPedidosApp\adapters\ms;

use MsPedidosApp\adapters\ms\base\MsApi;
use RdKafka\Exception;

class ProductionGateway extends MsApi
{
    private static ?self $instance = null;

    /**
     * Factoring
     */
    private function __construct(){}

    /**
     * @param $data
     * @return string
     * @throws Exception
     */
    public static function produce($data): string
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
        }
        $productionGateway = self::$instance;

        return $productionGateway->sendToQueue(json_encode($data), "{$_ENV['PRODUCTION_TOPIC']}");
    }

}
