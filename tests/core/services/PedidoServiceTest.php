<?php

namespace core\services;

use MongoDB\Driver\Exception\Exception;
use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\core\entities\Pedido;
use MsPedidosApp\core\services\PedidoService;
use PHPUnit\Framework\TestCase;

class PedidoServiceTest extends TestCase
{

    private $pedidoService;
    private $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(MongoRepository::class);
        $this->pedidoService = new PedidoService($this->repositoryMock);
    }

    /**
     * @throws Exception
     */
    public function testCreateSuccess()
    {
        // Simula um retorno bem-sucedido do método 'create' do repositório
        $this->repositoryMock->method('create')->willReturn(1);
        // Simula a obtenção do último ID inserido
        $this->repositoryMock->method('getLastInsertId')->willReturn('laksdhfupi4hq98hh8hoiij');
        // Simula o retorno do método 'show'
        $this->repositoryMock->method('show')->willReturn(
            [
                "_id" => "65b6dca3c0695d7ba5083732",
                "status" => "INICIADO",
                "created_at" => "2024-01-28 23:00:51",
                "updated_at" => "2024-01-28 23:00:51"
            ]
        );

        $result = $this->pedidoService->create([]);

        $this->assertInstanceOf(Pedido::class, $result);
    }

    /**
     * @throws Exception
     */
    public function testCreateFails()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O pedido não foi criado");

        $this->repositoryMock->method('create')->willReturn(0);

        $this->pedidoService->create([]);
    }

    public function testUpdateWithValidStatus()
    {
        $this->repositoryMock->method('update')->willReturn(1);

        $result = $this->pedidoService->update('1', ['status' => 'EM_PREPARACAO']);

        $this->assertInstanceOf(Pedido::class, $result);
    }

    public function testUpdateThrowsExceptionWithInvalidStatus()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Status inválido");

        $this->pedidoService->update('1', ['status' => 'StatusInvalido']);
    }

    public function testUpdateFailsInRepository()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O pedido não foi alterado");

        $this->repositoryMock->method('update')->willReturn(0);

        $this->pedidoService->update('1', ['status' => 'EM_PREPARACAO']);
    }

    /**
     * @throws Exception
     */
    public function testUpdateWithItems()
    {
        $itensJson = '{
                    "itens": [
                        {
                            "nome": "Sprite",
                            "descricao": "Refrigerante",
                            "categoria": "bebidas",
                            "quantidade": 2,
                            "valor_unitario": 9.00
                        },
                        {
                            "nome": "Cheese Bacon Especial",
                            "descricao": "Cheese Bacon com molho de pimenta",
                            "categoria": "lanches",
                            "quantidade": 1,
                            "valor_unitario": 29.00
                        },
                        {
                            "nome": "Sunday Chocoloate",
                            "descricao": "Sorvete de creme com cobertura de Chocolate",
                            "categoria": "sobremesa",
                            "quantidade": 1,
                            "valor_unitario": 19.00
                        },
                        {
                            "nome": "Fritas Ervas Finas",
                            "descricao": "Porção Batata frita com tempero de ervas finas",
                            "categoria": "acompanhamento",
                            "quantidade": 2,
                            "valor_unitario": 12.00
                        }
                    ]
                }';
        $itens = json_decode($itensJson, true);

        $this->repositoryMock->method('update')->willReturn(1);

        $result = $this->pedidoService->update('1', $itens);

        $this->assertInstanceOf(Pedido::class, $result);
    }
}
