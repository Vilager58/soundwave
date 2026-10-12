<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Auth;
use App\Models\Database;
use App\Models\User\UserRepository;
session_start();
header('Content-Type: application/json');

$db = new Database('mysql:host=localhost;dbname=soundwave;port=3306', 'root', '');

$requestUri = parse_url($_SERVER['REQUEST_URI']);

$route = str_replace('/api/v1', '', $requestUri['path']);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$routeKey = $requestMethod . ':' . $route;
$routes = require __DIR__ . '/../src/routes.php';

if (empty($_POST)) {
    $rawInput = file_get_contents('php://input');
    if (!empty($rawInput)) {
        $json = json_decode($rawInput, true);
        if (is_array($json)) {
            $_POST = $json;
        }
    }
}

try{
    if (!isset($routes[$routeKey])) {
        http_response_code(404);
        throw new Exception('Route not found');
    }
    [$controllerClass, $methodName] = $routes[$routeKey];
    $controller = new $controllerClass($db);
    $response = $controller->$methodName();
    echo json_encode($response);
} catch ( \Exception $e ) {
    if (http_response_code() === 200) {
        http_response_code(401);
    }
    echo json_encode(["message" => $e->getMessage()]);
}

