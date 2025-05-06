<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\RegisterController;

require __DIR__ . '/controllers/homePageController.php';
require __DIR__ . '/controllers/loginPageController.php';
require __DIR__ . '/controllers/registerPageController.php';


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
    echo "Pagina nu este disponibilă.";

}