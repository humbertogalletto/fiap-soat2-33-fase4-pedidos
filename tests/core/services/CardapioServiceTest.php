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
    public function testCreateFailsToInsert()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Categoria inválida");
        $this->repositoryMock->method('create')->willReturn(0);

        $this->cardapioService->create(['categoria' => 'CategoriaValida']);
    }
}
