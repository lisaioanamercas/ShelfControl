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
$controller = new LandingController();

$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/ShelfControl', '', $_SERVER['REQUEST_URI']);



if ($request == '/') {
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
elseif (strstr($request,'/search-books')) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query = $_GET['query'] ?? '';
        $exploreController->searchBooks($query);
    }
}
elseif($request=='/delete-review')
{
    if($_SERVER['REQUEST_METHOD']=='DELETE') {
        $bookController->deleteReview();
    } else {
        http_response_code(405); 
    }
}
elseif (strstr($request ,'/search-users')) {

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
         $socialController = new \App\Controllers\SocialController();
         $socialController->searchUsers();
    }else {
        http_response_code(405); 
    }
  
}
elseif (strstr($request,'/search')) {

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $exploreController->exploreGet();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
    
}

elseif (strstr($request,'/book-details')) {

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
       $bookController->showDetails();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
    
}
elseif ($request =='/update-book') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookController->updateBook();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts POST requests.";
    }
} 
else if($request=='/add-review') {
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookController->addReview();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts POST requests.";
    }
}
elseif ($request == '/admin/add-book') {
    $adminController = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $adminController->addBookPost();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }
}
elseif ($request == '/toread') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $userBooksController = new \App\Controllers\UserBooksController();
        $userBooksController->toReadBooks();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }
}

elseif ($request == '/library') {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $userBooksController = new \App\Controllers\UserBooksController();
            $userBooksController->ownedBooks();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }

}
elseif ($request == '/read') {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $userBooksController = new \App\Controllers\UserBooksController();
        $userBooksController->readBooks();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }

}
elseif ($request == '/news') {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
         $newsController->index();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }
   
}
elseif ($request == '/admin/books') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
         $adminController = new \App\Controllers\AdminController();
         $adminController->showAdminBooks();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }

}
elseif ($request == '/library-all') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $userBooksController = new \App\Controllers\UserBooksController();
        $userBooksController->allBooksLibrary();
    } else {
        header('Location: /ShelfControl/home');
        exit;
    }
   
}
elseif (preg_match('/^\/books\/author\/(.+)$/', $request, $matches)) {
    $bookController = new \App\Controllers\BookController();
    $author = urldecode($matches[1]);
    $bookController->showBooksByAttribute('author', $author);
}
elseif (preg_match('/^\/books\/publisher\/(.+)$/', $request, $matches)) {
    $bookController = new \App\Controllers\BookController();
    $publisher = urldecode($matches[1]);
    $bookController->showBooksByAttribute('publisher', $publisher);
}
elseif (preg_match('/^\/books\/translator\/(.+)$/', $request, $matches)) {
    $bookController = new \App\Controllers\BookController();
    $translator = urldecode($matches[1]);
    $bookController->showBooksByAttribute('translator', $translator);
}
elseif (preg_match('/^\/books\/subpublisher\/(.+)$/', $request, $matches)) {
    $bookController = new \App\Controllers\BookController();
    $subpublisher = urldecode($matches[1]);
    $bookController->showBooksByAttribute('subpublisher', $subpublisher);
}
elseif (preg_match('/^\/books\/genre\/(.+)$/', $request, $matches)) {
    $bookController = new \App\Controllers\BookController();
    $genre = urldecode($matches[1]);
    $bookController->showBooksByAttribute('genre', $genre);
}
elseif (preg_match('/^\/books\/edition\/(.+)$/', $request, $matches)) {
    $bookController = new \App\Controllers\BookController();
    $edition = urldecode($matches[1]);
    $bookController->showBooksByAttribute('edition', $edition);
}
elseif ($request == '/social') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $socialController = new \App\Controllers\SocialController();
        $socialController->index();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
}
elseif ($request == '/user-groups') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $socialController = new \App\Controllers\SocialController();
        $socialController->getUserGroups();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
}
elseif ($request == '/create-group') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $socialController = new \App\Controllers\SocialController();
       $socialController->createGroup();
    }else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
    
}
elseif ($request == '/add-member') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $socialController = new \App\Controllers\SocialController();
        $socialController->addMember();
    }else {
        http_response_code(405); 
        echo "This endpoint only accepts POST requests.";
    }

}
elseif ($request == '/start-group-reading') {
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $socialController = new \App\Controllers\SocialController();
          $socialController->startGroupReading();
    }else {
        http_response_code(405); 
        echo "This endpoint only accepts POST requests.";
    }
 
}
elseif ($request == '/rss') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $newsController->getNews();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }

}
elseif ($request == '/export/stats/csv') {
    $exportController = new \App\Controllers\ExportController();
    $exportController->exportStatsCSV();
}
elseif($request =='/news/add')
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newsController->addNewsPost();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts POST requests.";
    }
}
elseif ($request == '/export/stats/docbook') {
    $exportController = new \App\Controllers\ExportController();
    $exportController->exportStatsDocBook();
}
elseif($request=='/api/libraries')
{
    $exploreController->getLibraries();
}
elseif ($request == '/admin/delete-book') {
    $adminController = new \App\Controllers\AdminController();

    if( $_SERVER['REQUEST_METHOD'] == 'DELETE') {
         $adminController->deleteBook();
    }
}
elseif (strpos($request, '/admin/get-book-details') === 0) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $adminController = new \App\Controllers\AdminController();
        $adminController->getBookDetails();
    } else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
   
}
elseif ($request == '/admin/update-book') {
    $adminController = new \App\Controllers\AdminController();
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $adminController->updateBook();
    }else {
        http_response_code(405); 
        echo "This endpoint only accepts GET requests.";
    }
}
