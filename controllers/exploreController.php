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
      public function getStreetFromCoords($lat, $lon) {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon&zoom=17&addressdetails=1";
        $opts = [
            "http" => [
                "header" => "User-Agent: MyApp/1.0"
            ]
        ];
        $context = stream_context_create($opts);
        $result = @file_get_contents($url, false, $context);
        if ($result === FALSE) {
            return 'Stradă necunoscută';
        }
        $data = json_decode($result, true);
        return $data['address']['road'] ?? 'Stradă necunoscută';
}

    public function getLibraries() {
        $jwt = new BaseController();
        require __DIR__ . '/../models/dbConnection.php';

        $decoded = $jwt->validateJWT($_COOKIE['jwt']);
        $userEmail = $decoded->data->email;
        $userModel = new UserModel($conn);
       $city = $userModel->getCityByEmail($userEmail);
      //  $city = 'Iași';

        $query = '[out:json][timeout:25];
            area["name"="' . $city . '"]["boundary"="administrative"]["admin_level"~"^(8|6)$"]->.searchArea;
            (
            node["shop"="books"](area.searchArea);
            way["shop"="books"](area.searchArea);
            relation["shop"="books"](area.searchArea);
            );
            out body;
            >;
            out skel qt;';

        $url = "https://overpass-api.de/api/interpreter?data=" . urlencode($query);
        $response = file_get_contents($url);

        if ($response === FALSE) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["error" => "API request failed"]);
            exit;
        }

        $data = json_decode($response, true);
        $libraries = [];

        if (isset($data['elements'])) {
            foreach ($data['elements'] as $element) {
                $name = $element['tags']['name'] ?? 'Bibliotecă fără nume';
                $street = 'Stradă necunoscută';
                $lat = $element['lat'] ?? $element['center']['lat'] ?? null;
                $lon = $element['lon'] ?? $element['center']['lon'] ?? null;

                if ($lat && $lon) {
                    $street = $this->getStreetFromCoords($lat, $lon);
                }

                $libraries[] = [
                    'name' => $name,
                    'address' => $street
                ];
            }
        }
        $libraries = array_slice($libraries, 0, 5);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($libraries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    public function removeDuplicateBooks($books) {
        $uniqueBooks = [];
        $seenTitles = [];

        foreach ($books as $book) {
            if (!in_array($book['title'], $seenTitles)) {
                $uniqueBooks[] = $book;
                $seenTitles[] = $book['title'];
            }
        }

        return $uniqueBooks;
    }
    public function applyQueryFilter($query) {
       
        if (empty($query)) {
            return [];
        }

        $query = strtolower($query);
        $filteredBooks = [];

        foreach ($this->books as $book) {
            if (strpos(strtolower($book['title']), $query) !== false || 
                strpos(strtolower($book['authors']), $query) !== false) {
                $filteredBooks[] = $book;
            }
        }

        return $filteredBooks;
    }
     
    public function searchBooks($query)
    {  
        require_once __DIR__ . '/../models/dbConnection.php';
        $bookModel = new BookModel($conn);
       // echo json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if(empty($query)) {
            $query = 'love';
        }
        $dbBooks = $bookModel->searchBooks($query);
        if ($dbBooks === false) {
            error_log("Database query failed for search: " . $query);
            http_response_code(500);
            echo json_encode(['error' => 'Database query failed']);
            return;
        }

         $query = str_replace('+', ' ', $query);

        
        error_log(print_r($dbBooks, true));

    
        error_log("Search query: " . $query);
        $googleBooks = [];
        $googleUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode("intitle:$query") . '&maxResults=20';
        $googleResponse = file_get_contents($googleUrl);
        if ($googleResponse === FALSE) {
         error_log("Google Books API request failed! URL: $googleUrl");
}
        if ($googleResponse) {
            $googleData = json_decode($googleResponse, true);
            if (!empty($googleData['items'])) {
                $googleBooks = $googleData['items'];
            }
        }
       $normalizedDbBooks = [];
        foreach ($dbBooks as $book) {
            $normalizedDbBooks[] = [
                'id' => $book['ID'] ?? $book['id'],
                'volumeInfo' => [
                    'title' => $book['TITLE'] ?? $book['title'],
                    'authors' => isset($book['AUTHORS']) ? explode(',', $book['AUTHORS']) : (isset($book['authors']) ? explode(',', $book['authors']) : []),
                    'description' => $book['description'] ?? $book['description'],
                    'publishedDate' => $book['PUBLISHEDDATE'] ?? $book['publishedDate']??' ',
                    'imageLinks' => [
                    'thumbnail' => $book['IMAGELINKS'] ?? $book['imageLinks'],
                    'categories' => isset($book['CATEGORIES']) ? explode(',', $book['CATEGORIES']) : (isset($book['categories']) ? explode(',', $book['categories']) : []),
                    ]
                ]
            ];
        }

       $allBooks = array_merge($normalizedDbBooks, $googleBooks);
        header('Content-Type: application/json');
        echo json_encode(['books' =>  $allBooks]);
    }


    
}




