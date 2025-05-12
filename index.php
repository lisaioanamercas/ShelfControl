<?php
use App\Controllers\LoginController;
use App\Controllers\LandingController;
use App\Controllers\RegisterController;
use App\Controllers\HomeController;
use App\Controllers\BookController;


require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$registerController = new RegisterController();
$loginController = new LoginController();
$homeController = new HomeController();
$landingController = new LandingController();
$bookController = new BookController();

$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/ShelfControl', '', $_SERVER['REQUEST_URI']);



if ($request == '/') {
    $controller = new LandingController();
    $controller->index();

} elseif ($request == '/login') {

     if($_SERVER['REQUEST_METHOD']=='POST')
    {
       $loginController->loginPost();
    }
    else
    {
      $loginController->loginGet();
    }

} elseif ($request == '/register') {

    if($_SERVER['REQUEST_METHOD']=='POST')
    {
       $registerController->registerPost();
    }
    else
    {
      $registerController->registerGet();
    }

} 
elseif($request=='/logout')
{
    $loginController->logout();
}
elseif ($request == '/home') {

  if($_SERVER['REQUEST_METHOD']=='POST')
    {
       $homeController->homePost();
    }
    else
    {
      $homeController->HomeGet();
    }

} 
elseif (strstr($request,'/book-details')) {

    $bookController->bookGet();
 

}
else {
    http_response_code(404);
    echo "Pagina nu este disponibilă.";

}