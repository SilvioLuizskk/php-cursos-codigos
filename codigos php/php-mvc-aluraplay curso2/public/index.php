<?php

declare(strict_types=1);

use Alura\Mvc\Controller\{
    Controller,
    DeleteVideoController,
    EditVideoController,
    Error404Controller,
    LoginController,
    LoginFormController,
    LogoutController,
    NewVideoController,
    VideoFormController,
    VideoListController
};
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$dbPath = __DIR__ . '/../banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");
$videoRepository = new VideoRepository($pdo);

$routes = require_once __DIR__ . '/../config/routes.php';

$pathInfo = $_SERVER['PATH_INFO'] ?? '/';
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

session_start();
session_regenerate_id();

// Inicializar $_SESSION como array se não existir
if (!isset($_SESSION)) {
    $_SESSION = [];
}
$isLoginRoute = $pathInfo === '/login';
if (!array_key_exists('logado', $_SESSION) && !$isLoginRoute) {
    header('Location: /login');
    return;
}

// Criar o PSR-7 ServerRequest
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();

$key = "$httpMethod|$pathInfo";
if (array_key_exists($key, $routes)) {
    $controllerClass = $routes[$key];
    $controller = new $controllerClass($videoRepository);
    
    /** @var Controller $controller */
    $response = $controller->processaRequisicao($serverRequest);
    
    // Enviar headers da response
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $headerName => $headerValues) {
        foreach ($headerValues as $headerValue) {
            header("$headerName: $headerValue");
        }
    }
    
    // Enviar body da response
    echo $response->getBody()->getContents();
} else {
    $controller = new Error404Controller();
    $response = $controller->processaRequisicao($serverRequest);
    
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $headerName => $headerValues) {
        foreach ($headerValues as $headerValue) {
            header("$headerName: $headerValue");
        }
    }
    
    echo $response->getBody()->getContents();
}