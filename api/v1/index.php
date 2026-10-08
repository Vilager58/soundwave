<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Auth;
use App\Models\Database;
use App\Models\User\UserRepository;
session_start();

$db = new Database('mysql:host=localhost;dbname=soundwave;port=3306', 'root', '');

$requestUri = parse_url($_SERVER['REQUEST_URI']);


$userRepository = new UserRepository($db);
$auth = new Auth($userRepository);
//$res = $userRepository->getPermissions(1);

$route = str_replace('/api/v1', '', $requestUri['path']);
//print_r($route);

//
//$routes = [
//    '/admin' => [],
//    '/status'
//    '/login'
//    '/logout'
//    '/register'
//    '/product'
//]
$response = [];

try{
    switch($route){
        case '/admin':
            if($auth->checkPermission('catalog.view')){
                echo 'каталогущи';
            } else {
                throw new Exception("not allowed 403");
            }
            break;

        case '/status':
            echo $auth->isLoggedIn() ? ' Залогинен ' : ' нет ';
            print_r($auth->getPermissions());
            break;


        case '/login':
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                if(!isset($_POST['login-login']) || !isset($_POST['login-password'])){
                    throw new Exception("Не хватает данных");
                }
                $auth->login($_POST['login-login'], $_POST['login-password']);

                $response = [
                    'status' => 'success',
                    'message' => "Успешная авторизация!"
                ];

            } else {
                echo throw new Exception("GET request method not allowed");
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(["error" => "Not Found"]);
            break;
    }
    echo json_encode($response);

} catch ( \Exception $e ) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

