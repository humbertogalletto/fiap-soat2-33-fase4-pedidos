<?php

namespace core\services;

use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\core\entities\Cardapio;
use MsPedidosApp\core\services\CardapioService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CardapioServiceTest extends TestCase
{

    private $cardapioService;
    private $repositoryMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(MongoRepository::class);
        $this->cardapioService = new CardapioService($this->repositoryMock);
    }

    /**
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function testCreateThrowsExceptionWhenNoCategory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Preencha a categoria do produto");

        $this->cardapioService->create([]);
    }

    /**
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function testCreateThrowsExceptionWithInvalidCategory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Categoria inválida");

        $this->cardapioService->create(['categoria' => 'CategoriaInexistente']);
    }

    /**
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function testCreateSuccess()
    {
        $this->repositoryMock->method('create')->willReturn(1);
        $this->repositoryMock->method('getLastInsertId')->willReturn('ljdasfhuehpecnkjnuquw989');
        $this->repositoryMock->method('show')->willReturn(
            [
                "nome" => "X SALADA",
                "descricao" => "Lanche com hamburguer, queijo e alface",
                "categoria" => "LANCHES",
                "valor" => "30.50"
            ]
        );

        $result = $this->cardapioService->create(['categoria' => 'LANCHES']);

        $this->assertInstanceOf(Cardapio::class, $result);
    }

    /**
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function testCreateFailsToInsertBadCategory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Categoria inválida");

        $this->cardapioService->create(['categoria' => 'CategoriaInvalida']);
    }

    public function testCreateFailsToInsert()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O item do cardápio não foi criado");
        $this->repositoryMock->method('create')->willReturn(0);

        $this->cardapioService->create(['categoria' => 'LANCHES']);
    }

    public function testUpdateValidCategory()
    {
        $this->repositoryMock->method('update')->willReturn(1);

        $body = [
            "nome" => "Pepsi Grande",
            "descricao" => "Refrigerante Pepsi cola de 500ml",
            "categoria" => "BEBIDAS",
            "valor" => 9.50
        ];

        $result = $this->cardapioService->update('huhuhuen9h98239dh9', $body);

        $this->assertInstanceOf(Cardapio::class, $result);
    }

    public function testUpdateInvalidCategory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Categoria inválida");

        $body = [
            "nome" => "Pepsi Grande",
            "descricao" => "Refrigerante Pepsi cola de 500ml",
            "categoria" => "Categoria",
            "valor" => 9.50
        ];

        $this->cardapioService->update('huhuhuen9h98239dh9', $body);
    }

    public function testUpdateFail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O item do cardápio não foi alterado");
        $this->repositoryMock->method('update')->willReturn(0);


        $this->cardapioService->update('huhuhuen9h98239dh9', []);
    }

    // Testes para o método `delete`
    public function testDeleteSuccessful()
    {
        $this->repositoryMock->method('delete')->willReturn(1);

        $result = $this->cardapioService->delete('huhuhuen9h98239dh9');

        $this->assertEmpty($result);
        $this->assertIsArray($result);
    }

    public function testDeleteFails()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O item do cardápio não foi deletado");

        $this->repositoryMock->method('delete')->willReturn(0);

        $this->cardapioService->delete('huhuhuen9h98239dh9');
    }

    public function testShow()
    {
        $this->repositoryMock->method('show')->willReturn(
            [
                "nome" => "X SALADA",
                "descricao" => "Lanche com hamburguer, queijo e alface",
                "categoria" => "LANCHES",
                "valor" => "30.50"
            ]
        );

        $result = $this->cardapioService->show('huhuhuen9h98239dh9');

        $this->assertInstanceOf(Cardapio::class, $result);

    }


    /**
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function testList()
    {
        $this->repositoryMock->method('list')->willReturn(
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

        $result = $this->cardapioService->list();

        $this->assertIsArray($result);
        $this->assertInstanceOf(Cardapio::class, $result[0]);
    }

    public function testCategoriasList()
    {
        $result = $this->cardapioService->categoriasList();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('LANCHES',$result);
    }

}
