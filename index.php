<?php
use App\Controllers\AuthController;
use App\Controllers\HomeController;

require __DIR__ . '/controllers/homePageController.php';
require __DIR__ . '/controllers/loginPageController.php';


$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/ShelfControl', '', $_SERVER['REQUEST_URI']);



if ($request == '/') {
    $controller = new HomeController();
    $controller->index();
} elseif ($request == '/login') {
    echo "Aici";
    $controller = new AuthController();
    $controller->login();
} /*elseif ($request == '/logout') {
    // Deconectare
    $controller = new AuthController();
    $controller->logout();
} */else {
    echo "Pagina nu este disponibilă.";

}