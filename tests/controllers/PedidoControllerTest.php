<?php

namespace controllers;

use MsPedidosApp\adapters\ui\CreateAdapter;
use MsPedidosApp\adapters\ui\DeleteAdapter;
use MsPedidosApp\adapters\ui\ListAdapter;
use MsPedidosApp\adapters\ui\ShowAdapter;
use MsPedidosApp\adapters\ui\UpdateAdapter;
use MsPedidosApp\controllers\PedidoController;
use MsPedidosApp\core\entities\Pedido;
use MsPedidosApp\core\services\PedidoService;
use MsPedidosApp\core\types\EnumStatus;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class PedidoControllerTest extends TestCase
{
    protected $pedidoController;

    protected $pedidoService;


    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->pedidoService = $this->createMock(PedidoService::class);
        $this->pedidoController = new PedidoController($this->pedidoService);
    }

    public function testList()
    {
        $this->pedidoService->method('list')->willReturn(
            [
                [
                    "nome" => "X SALADA",
                    "descricao" => "Lanche com hamburguer, queijo e alface",
                    "categoria" => "LANCHES",
                    "valor" => "30.50"
                ],
                [
                    "nome" => "X SALADA",
                    "descricao" => "Lanche com hamburguer, queijo e alface",
                    "categoria" => "LANCHES",
                    "valor" => "30.50"
                ],
                [
                    "nome" => "X SALADA",
                    "descricao" => "Lanche com hamburguer, queijo e alface",
                    "categoria" => "LANCHES",
                    "valor" => "30.50"
                ],
            ]
        );

        $result = $this->pedidoController->list();

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testDelete()
    {
        $this->pedidoService->method('delete')->willReturn([]);

        $result = $this->pedidoController->delete('alkdjahlskdjfhauh8p98h');

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testListByStatus()
    {
        $this->pedidoService->method('list')->willReturn(
            [
                [
                    "nome" => "X SALADA",
                    "descricao" => "Lanche com hamburguer, queijo e alface",
                    "categoria" => "LANCHES",
                    "status" => "EM_PREPARACAO",
                    "valor" => "30.50"
                ],
                [
                    "nome" => "X SALADA",
                    "descricao" => "Lanche com hamburguer, queijo e alface",
                    "categoria" => "LANCHES",
                    "status" => "EM_PREPARACAO",
                    "valor" => "30.50"
                ],
                [
                    "nome" => "X SALADA",
                    "descricao" => "Lanche com hamburguer, queijo e alface",
                    "categoria" => "LANCHES",
                    "status" => "EM_PREPARACAO",
                    "valor" => "30.50"
                ],
            ]
        );

        $result = $this->pedidoController->listByStatus('EM_PREPARACAO');

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testShow()
    {
        $this->pedidoService->method('show')->willReturn(new Pedido);

        $result = $this->pedidoController->show('kasjdhfao74fh7h08a7hc0a');

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testStatusList()
    {
        $this->pedidoService->method('statusList')->willReturn(EnumStatus::getList());

        $result = $this->pedidoController->statusList();

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testCreate()
    {
        $request = [
            "nome" => "Pepsi Grande",
            "descricao" => "Refrigerante Pepsi cola de 500ml",
            "categoria" => "BEBIDAS",
            "valor" => 9.50
        ];

        $this->pedidoService->method('create')->willReturn(new Pedido);

        $result = $this->pedidoController->create($request);

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testUpdate()
    {
        $request = [
            "nome" => "Pepsi Grande",
            "descricao" => "Refrigerante Pepsi cola de 500ml",
            "categoria" => "BEBIDAS",
            "valor" => 9.50
        ];

        $this->pedidoService->method('update')->willReturn(new Pedido);

        $result = $this->pedidoController->update('kUH0837H37h937h98', $request);

        $this->assertJson($result);
        $this->assertNull($result->error);
    }
}
