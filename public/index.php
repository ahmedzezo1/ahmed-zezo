<?php
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Session;

Session::init();

$request = new Request();
$response = new Response();
$router = new Router($request, $response);

// Load routes
$routesFile = dirname(__DIR__) . '/routes/web.php';
if (is_file($routesFile)) {
    /** @var App\Core\Router $router */
    require $routesFile;
}

$router->dispatch();
