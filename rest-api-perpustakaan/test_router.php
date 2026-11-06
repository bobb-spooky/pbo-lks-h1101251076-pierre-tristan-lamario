<?php

require_once __DIR__ . '/Core/Request.php';
require_once __DIR__ . '/Core/Response.php';
require_once __DIR__ . '/Core/Router.php';
require_once __DIR__ . '/Core/Container.php';

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\Container;

// =============================
// TEST ROUTER
// =============================

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/api/hello';

$request = new Request();
$router = new Router();

$router->get('/api/hello', function () {
    Response::json([
        'message' => 'Router bekerja!'
    ]);
});

ob_start();

$router->dispatch($request);

$output = ob_get_clean();

$data = json_decode($output, true);

if (isset($data['message']) && $data['message'] === 'Router bekerja!') {
    echo "Router bekerja! Endpoint /api/hello terpanggil.\n";
} else {
    echo "Router gagal.\n";
}

// =============================
// TEST CONTAINER
// =============================

$container = new Container();

$container->bind('hello', function () {
    return 'Container bekerja!';
});

$result = $container->make('hello');

if ($result === 'Container bekerja!') {
    echo "Container bekerja!\n";
} else {
    echo "Container gagal.\n";
}
