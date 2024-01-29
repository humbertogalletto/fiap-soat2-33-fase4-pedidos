<?php

namespace MsPedidosApp\core\services;

use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\ms\PaymentGateway;
use MsPedidosApp\adapters\ms\ProductionGateway;
use MsPedidosApp\core\entities\Pedido;
use MsPedidosApp\core\interfaces\IPagamentoService;

class PagamentoService implements IPagamentoService
{
    /**
     * @throws GuzzleException
     */
    public function process(array $data): bool
    {
        if($data['confirm']) {
            return $this->confirm();
        }

        return false;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function confirm(): bool
    {
        ProductionGateway::produce(['status' => true]);

        return true;
    }

    public function order(Pedido $pedido): array
    {
        $itens = [];
        foreach($pedido->itens as $item){
            $itens[] = [
                'name' => $item['nome'],
                'amount' => $item['quantidade'],
                'price' => $item['valor_unitario']
            ];
        }

        $data = [
            'externalId'=> $pedido->_id,
            'total' => $pedido->valor_total,
            'itens' => $itens,
            'status' => 0
        ];

        return PaymentGateway::order($data);
    }
}
