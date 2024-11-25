<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ContractController;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$request = $creator->fromGlobals();
$path = $request->getUri()->getPath();

switch ($path) {
    case '/':
        $response = $psr17Factory->createResponse(404);
        $response->getBody()->write("404 - Page Not Found");
        break;
    default:
        $controller = new ContractController();
        $response = $controller->listContracts($request, $psr17Factory->createResponse());
}

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header("{$name}: {$value}", false);
    }
}
http_response_code($response->getStatusCode());
echo $response->getBody();