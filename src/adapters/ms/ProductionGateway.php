<?php

namespace MsPedidosApp\adapters\ms;

use MsPedidosApp\adapters\ms\base\SendOut;

class ProductionGateway extends SendOut
{
    private static ?self $instance = null;

    private $endpoint;
    private function __construct(){$this->setEndpoint();}

    /**
     * @param $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function produce($data): array
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
        }
        $paymentGateway = self::$instance;

        return json_decode($paymentGateway->post($paymentGateway->getEndpoint(), $data), true);
    }

    public function setEndpoint(){
        $this->endpoint = "{$_ENV['PRODUCTION_CONNECT']}/confirm";
    }

    public function getEndpoint(){
        return $this->endpoint;
    }



}