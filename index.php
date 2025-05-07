<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\RegisterController;


//require __DIR__ . '/controllers/homePageController.php';
//require __DIR__ . '/controllers/loginPageController.php';
//require __DIR__ . '/controllers/registerPageController.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/ShelfControl', '', $_SERVER['REQUEST_URI']);



if ($request == '/') {
    $controller = new HomeController();
    $controller->index();

} elseif ($request == '/login') {

    $controller = new AuthController();
    $controller->login();

} elseif ($request == '/register') {

    $controller = new RegisterController();
    $controller->register();

} else {
    http_response_code(404);
    echo "Pagina nu este disponibilă.";

}