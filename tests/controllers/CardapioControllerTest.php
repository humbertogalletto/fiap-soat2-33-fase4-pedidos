<?php

namespace controllers;

use MsPedidosApp\adapters\db\DBGateway;
use MsPedidosApp\api\FastFoodApp;
use MsPedidosApp\controllers\CardapioController;
use PHPUnit\Framework\TestCase;

class CardapioControllerTest extends TestCase
{
    public function testCreate()
    {
        $data = json_encode('{
                    "nome": "X Bacon",
                    "descricao": "Lanche com hamburguer, queijo e bacon",
                    "categoria": "LANCHES",
                    "valor": "32.00"
                }');

        $fastFoodApp = new FastFoodApp(DBGateway::getRepository());

        $fastFoodApp->create(CardapioController::class, $data);
    }

    public function testShow()
    {

    }

    public function testCategoriasList()
    {

    }

    public function testUpdate()
    {

    }

    public function testDelete()
    {

    }

    public function testList()
    {

    }


}
