<?php

namespace MsPedidosApp\adapters\ms;

use MsPedidosApp\adapters\ms\base\SendOut;

class PaymentGateway extends SendOut
{
    private static ?self $instance = null;

    private $endpoint;
    private function __construct(){$this->setEndpoint();}

    public static function order($data): array
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
        }
        $paymentGateway = self::$instance;

        return json_decode($paymentGateway->post($paymentGateway->getEndpoint(), $data), true);
    }

    public function setEndpoint(){
        $this->endpoint = "{$_ENV['PAYMENT_CONNECTION']}/order-confirm";
    }

    public function getEndpoint(){
        return $this->endpoint;
    }

}