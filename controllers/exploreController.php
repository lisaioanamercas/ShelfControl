<?php
namespace App\Controllers;
use App\Views\BaseView; 
use App\Models\BookModel;
use App\Models\UserModel;

class ExploreController{


    public function exploreGet(){

          $jwt = new BaseController();
        $isLoggedIn = $jwt->verifyLogin();
        if (!$isLoggedIn) {
            header('Location: /ShelfControl/login');
            exit;
        }

        $view= new BaseView();
        $data = [
            'pageTitle' => 'Explore',
            'currentPage' => 'library',
            'additionalCSS' => [
                '/ShelfControl/views/css/lib.css', 
                '/ShelfControl/views/css/dark-theme-explore.css',
                '/ShelfControl/views/css/bookPage/reviews.css',
                '/ShelfControl/views/css/fullLibrary.css',
                '/ShelfControl/views/css/book-section.css'


            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/explore.js',
                '/ShelfControl/views/scripts/darkTheme.js'
            ]
        ];
        $view->renderTemplate('explore',$data);
    }

   public function explorePost() {

    header('Content-Type: application/json');

    $jsonInput = file_get_contents('php://input');
    if (!$jsonInput) {
        http_response_code(400);
        echo json_encode(['error' => 'No data received']);
        exit;
    }

    require __DIR__ . '/../models/dbConnection.php';

    $bookModel = new BookModel($conn);
    
   
  

      $jwt = new BaseController();

    $decoded = $jwt->validateJWT($_COOKIE['jwt']);
    $userEmail = $decoded->data->email;
    $userModel = new UserModel($conn);
    $userId = $userModel->getUserIdByEmail($userEmail);
  

    $data = json_decode($jsonInput, true);
    $book= $data[0];

    $title = $book['title'];

     if($bookModel->getBookidByTitle($title)!=null)
    {     
        $bookId = $bookModel->getBookIdByTitle($title);
        if($bookModel->isBookInUserList($userId, $bookId)){
             echo json_encode(['message' => 'Cartea exista in to read deja!']);
            exit;
        }else {
            $bookModel->insertIntoUserBook($userId, $bookId);
            echo json_encode(['message' => 'Cartea a fost adaugata in to read!']);
            exit;
        }

    }
    else{
        
    $bookModel->importBooksFromJson($jsonInput);

    $bookId = $bookModel->getBookIdByTitle($title);
    $bookModel->insertIntoUserBook($userId, $bookId);
    
    echo json_encode(['message' => 'Carte salvată cu succes!']);
    exit;
   }
    
}
    public function getLibraries(){
    

            $jwt = new BaseController();
            
            require __DIR__ . '/../models/dbConnection.php';

            $decoded = $jwt->validateJWT($_COOKIE['jwt']);
            $userEmail = $decoded->data->email;

            $userModel = new UserModel($conn);
          //  $city=$userModel->getCityByEmail($userEmail);
          $city='Iasi';


    $query = '[out:json][timeout:25];
area["name"="Iași"]["boundary"="administrative"]["admin_level"~"^(8|6)$"]->.searchArea;
(
  node["shop"="books"](area.searchArea);
  way["shop"="books"](area.searchArea);
  relation["shop"="books"](area.searchArea);
);
out body;
>;
out skel qt;'
;

    $url = "https://overpass-api.de/api/interpreter?data=" . urlencode($query);
    $response = file_get_contents($url);

    if ($response === FALSE) {
        return json_encode(["error" => "API request failed"]);
    }

    $data = json_decode($response, true);
    $libraries = [];

    if (isset($data['elements'])) {
        foreach ($data['elements'] as $element) {
            

           $name = $element['tags']['name'] ?? 'Bibliotecă fără nume';
            $street = $element['tags']['addr:street'] ?? 'Stradă necunoscută';

            $libraries[] = [
                'name' => $name,
                'address' => $street
            ];
        }
    }

       header('Content-Type: application/json; charset=utf-8');

        echo json_encode($libraries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }
}




