<?php
require_once 'vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $requestUrl = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $routes = include_once 'src/api/routes.php';

    $matchedRoute = null;
    $matchedParams = [];

    foreach ($routes as $routeUrl => $handler) {
        $regex = '#^' . preg_replace('/{(\w+)}/', '(\w+)', $routeUrl) . '$#';

        if (preg_match($regex, $requestUrl, $matches)) {
            $matchedParams = array_slice($matches, 1);
            $matchedRoute = $handler[$method];
            break;
        }
    }

    if ($matchedRoute !== null) {
        echo call_user_func_array($matchedRoute, $matchedParams);
    } else {
        throw new \Exception("Not found", 404);
    }

} catch (\Exception $e) {
    error_log($e);
    echo "{$e->getCode()} - {$e->getMessage()} - {$e->getFile()} - {$e->getLine()}";
}