<?php
use App\Controllers\LoginController;
use App\Controllers\LandingController;
use App\Controllers\RegisterController;
use App\Controllers\ExploreController;
use App\Controllers\BookController;
use App\Controllers\HomeController;
use App\Controllers\SaveBookController;
use App\Controllers\NewsController;

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$registerController = new RegisterController();
$loginController = new LoginController();
$exploreController = new ExploreController();
$landingController = new LandingController();
$bookController = new BookController();
$homeController = new HomeController();
$newsController = new NewsController();
//$saveBookController = new SaveBookController();
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
elseif ($request == '/home') {

    if($_SERVER['REQUEST_METHOD']=='POST')
    {
       $homeController->homePost();
    }
    else
    {
      $homeController->homeGet();
    }

}
elseif($request=='/logout')
{
    $loginController->logout();
}

elseif (strstr($request,'/explore')) {
    if($_SERVER['REQUEST_METHOD']=='POST') {
        $exploreController->explorePost();
    } else {
        $exploreController->exploreGet();
    }
} 
elseif (strstr($request,'/search')) {
    // Route search requests to the explore controller !!!
    $exploreController->exploreGet();
}
// elseif ($request == '/update-progress') {
  
//     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//         $homeController->updateProgressPost();
//     } else {
//         http_response_code(405); // Method Not Allowed
//         echo "This endpoint only accepts POST requests.";
//     }
// }

elseif (strstr($request,'/book-details')) {

    $bookController->showDetails();
}
// asta e petru AJAX ca sa dea update la statusul cartii -- nu prea merge inca
elseif ($request =='/update-book') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookController->updateBook();
    } else {
        http_response_code(405); // Method Not Allowed
        echo "This endpoint only accepts POST requests.";
    }
} 
// Admin API endpoints - only for processing form submissions
elseif ($request == '/admin/add-book') {
    $adminController = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $adminController->addBookPost();
    } else {
        // Instead of an error, redirect to home page
        header('Location: /ShelfControl/home');
        exit;
    }
}
elseif ($request == '/toread') {
    $userBooksController = new \App\Controllers\UserBooksController();
    $userBooksController->toReadBooks();
} 
elseif ($request == '/library') {
    $userBooksController = new \App\Controllers\UserBooksController();
    $userBooksController->ownedBooks();
}
elseif ($request == '/news') {
    $newsController->index();
}
elseif ($request == '/admin/books') {
    $adminController = new \App\Controllers\AdminController();
    $adminController->showAdminBooks();
}
