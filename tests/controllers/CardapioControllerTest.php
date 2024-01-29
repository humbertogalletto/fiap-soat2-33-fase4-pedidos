<?php

namespace controllers;

use MsPedidosApp\adapters\ui\CreateAdapter;
use MsPedidosApp\adapters\ui\DeleteAdapter;
use MsPedidosApp\adapters\ui\ListAdapter;
use MsPedidosApp\adapters\ui\ShowAdapter;
use MsPedidosApp\adapters\ui\UpdateAdapter;
use MsPedidosApp\controllers\CardapioController;
use MsPedidosApp\core\entities\Cardapio;
use MsPedidosApp\core\services\CardapioService;
use MsPedidosApp\core\types\EnumCategorias;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CardapioControllerTest extends TestCase
{
    protected CardapioController $cardapioController;

    protected MockObject $cardapioServiceMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cardapioServiceMock = $this->createMock(CardapioService::class);
        $this->cardapioController = new CardapioController($this->cardapioServiceMock);
    }

    public function testCategoriasList()
    {
        $this->cardapioServiceMock->method('categoriasList')->willReturn(EnumCategorias::getList());

        $result = $this->cardapioController->categoriasList();

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

        $this->cardapioServiceMock->method('create')->willReturn(new Cardapio);

        $result = $this->cardapioController->create($request);

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

        $this->cardapioServiceMock->method('update')->willReturn(new Cardapio);

        $result = $this->cardapioController->update('kUH0837H37h937h98', $request);

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testShow()
    {
        $this->cardapioServiceMock->method('show')->willReturn(new Cardapio);

        $result = $this->cardapioController->show('kasjdhfao74fh7h08a7hc0a');

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testList()
    {
        $this->cardapioServiceMock->method('list')->willReturn(
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

        $result = $this->cardapioController->list();

        $this->assertJson($result);
        $this->assertNull($result->error);
    }

    public function testDelete()
    {
        $this->cardapioServiceMock->method('delete')->willReturn([]);

        $result = $this->cardapioController->delete('alkdjahlskdjfhauh8p98h');

        $this->assertJson($result);
        $this->assertNull($result->error);
    }
}
