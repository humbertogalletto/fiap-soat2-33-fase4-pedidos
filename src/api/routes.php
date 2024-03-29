<?php

namespace App;


use MsPedidosApp\adapters\db\DBGateway;
use MsPedidosApp\api\FastFoodApp;
use MsPedidosApp\controllers\CardapioController;
use MsPedidosApp\controllers\ClienteController;
use MsPedidosApp\controllers\PagamentoController;
use MsPedidosApp\controllers\PedidoController;

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$routes = [];

$command = 'php /src/adapters/queue/QueueGateway.php > /dev/null 2>&1 &';
shell_exec($command);

$fastFoodApp = new FastFoodApp(DBGateway::getRepository());

// Home - Exibe o cardápio
$routes['/'] = [
    'GET' => function () use ($fastFoodApp) {
        return $fastFoodApp->list(CardapioController::class);
    }
];

// Clientes
$routes['/clientes'] = [
    'POST' => function () use ($data, $fastFoodApp) {
        return $fastFoodApp->create(ClienteController::class, $data);
    },
    'GET' => function ($params = null) use ($fastFoodApp){
        return $fastFoodApp->list(ClienteController::class, $params);
    }
];

$routes['/clientes/{id}'] = [
    'PUT' => function ($id) use ($data, $fastFoodApp) {
        return $fastFoodApp->update(ClienteController::class, $id, $data);
    },
    'DELETE' => function ($id) use($fastFoodApp) {
        return $fastFoodApp->delete(ClienteController::class, $id);
    },
    'GET' => function ($id) use($fastFoodApp) {
        return $fastFoodApp->show(ClienteController::class, $id);
    }
];

$routes['/clientes/cpf/{cpf}'] = [
    'GET' => function ($cpf) use($fastFoodApp) {
        return $fastFoodApp->query(ClienteController::class, $cpf, 'showByCpf');
    }
];
// Pedidos
$routes['/pedido'] = [
    'POST' => function () use ($data, $fastFoodApp) {
        return $fastFoodApp->create(PedidoController::class, $data);
    },
    'GET' => function () use ($fastFoodApp) {
        return $fastFoodApp->list(PedidoController::class);
    }
];

$routes['/pedidos'] = [
    'GET' => function () use ($fastFoodApp) {
        return $fastFoodApp->query(PedidoController::class, [], 'pedidos');
    }
];

$routes['/pedido/{id}'] = [
    'PUT' => function ($id) use ($data, $fastFoodApp) {
        return $fastFoodApp->update(PedidoController::class, $id, $data);
    },
    'DELETE' => function ($id) use($fastFoodApp) {
        return $fastFoodApp->delete(PedidoController::class, $id);
    },
    'GET' => function ($id) use($fastFoodApp) {
        return $fastFoodApp->show(PedidoController::class, $id);
    }
];

$routes['/pedido-status'] = [
    'GET' => function () use($fastFoodApp) {
        return $fastFoodApp->query(PedidoController::class, [], 'statusList');
    }
];

$routes['/pedido-status/{status}'] = [
    'GET' => function ($status) use($fastFoodApp) {
        return $fastFoodApp->query(PedidoController::class, $status, 'listByStatus');
    }
];

// Cardapio
$routes['/cardapio'] = [
    'POST' => function () use ($data, $fastFoodApp) {
        return $fastFoodApp->create(CardapioController::class, $data);
    },
    'GET' => function () use($fastFoodApp) {
        return $fastFoodApp->list(CardapioController::class);
    }
];

$routes['/cardapio/{id}'] = [
    'PUT' => function ($id) use ($data, $fastFoodApp) {
        return $fastFoodApp->update(CardapioController::class, $id, $data);
    },
    'DELETE' => function ($id) use($fastFoodApp){
        return $fastFoodApp->delete(CardapioController::class, $id);
    },
    'GET' => function ($id) use($fastFoodApp){
        return $fastFoodApp->show(CardapioController::class, $id);
    }
];

$routes['/categorias'] = [
    'GET' => function () use($fastFoodApp) {
        return $fastFoodApp->query(CardapioController::class, [], 'categoriasList');
    }
];

// Pagamento
$routes['/order-confirm'] = [
    'POST' => function () use ($data, $fastFoodApp) {
        return $fastFoodApp->query(PagamentoController::class, [], $data, 'process');
    },
];

// Healthcheck
$routes['/healthcheck'] = [
    'GET' => function () {
        return json_encode(['status' => 'ok']);
    }
];


return $routes;

