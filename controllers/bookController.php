<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Views\BaseView;


class BookController{
    private $jwt;

    public function __construct() {
        $this->jwt = new BaseController();
    }


    public function test(){
          if (isset($_GET['id'])) {
            $bookId = $_GET['id'];
            $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . $bookId;

            $response = file_get_contents($apiUrl);
            if ($response) {
                header('Content-Type: application/json');
                echo $response;
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Book not found"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No book ID provided"]);
        }
    
    }
    public function lookInApi($bookId){
        $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . $bookId;

        $response = file_get_contents($apiUrl);
        if ($response) {
           $data = json_decode($response, true);
           $templateData= [ 
            'book_id' => $data['id'],
            'book_title' => $data['volumeInfo']['title'] ?? 'N/A',
            'book_author' => $data['volumeInfo']['authors'] ? implode(', ', $data['volumeInfo']['authors']) : 'N/A',
            'book_translator' => isset($data['volumeInfo']['translator']) ? implode(', ', $data['volumeInfo']['translator']) : 'N/A',
            'book_genre' => isset($data['volumeInfo']['categories']) ? implode(', ', $data['volumeInfo']['categories']) : 'N/A',
            'book_publication_year' => $data['volumeInfo']['publishedDate'] ?? 'N/A',
            'book_image_url' => $data['volumeInfo']['imageLinks']['thumbnail'] ?? 'assets/img/default-book.png',
            'book_description' => $data['volumeInfo']['description'] ?? 'No description available.',
            'book_page_count' => $data['volumeInfo']['pageCount'] ?? 'N/A',
            'book_isbn' => isset($data['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $data['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'N/A',
            'book_language' => $data['volumeInfo']['language'] ?? 'N/A',
            'book_publisher' => $data['volumeInfo']['publisher'] ?? 'N/A',
            'book_sub_publisher' => 'N/A',
            'book_source_api' => 'Google Books API',
            'is_owned' => false, 
            'reading_status' => 'Put status here',
            'google_id' => $data['id'],
            'pages_read' => 0,
            'additionalCSS' => [
                '/ShelfControl/views/css/book.css',
                '/ShelfControl/views/css/bookPage/book-info.css',
                '/ShelfControl/views/css/bookPage/reading-progress.css',
                '/ShelfControl/views/css/bookPage/similar-books.css',
                '/ShelfControl/views/css/bookPage/dark-theme-book.css',
                '/ShelfControl/views/css/bookPage/reviews.css'

            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/book.js',
                '/ShelfControl/views/scripts/review.js'
            ]
            ];

            // Render the view
            $view = new BaseView();
            $view->renderTemplate('book', $templateData);


        } else {
            http_response_code(404);
            echo json_encode(["error" => "Book not found"]);
        }


    }

    public function showDetails(){
        //caut ID-ul cartii
        if(!isset($_GET['id']))
        {
            header('Location: /ShelfControl/home');
            exit;
        }

        $bookId = $_GET['id'];

        //iau ID utilizator curent din JWT
        $isLoggedIn = $this->jwt->verifyLogin();
        $userId = null;
        if ($isLoggedIn) {
            $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
            $email = $decoded->data->email;
            
            
            require_once __DIR__ . '/../models/dbConnection.php';
            
            $userModel = new \App\Models\UserModel($conn);
            $userId = $userModel->getUserIdByEmail($email);
        }
        
        $bookModel = new BookModel($conn);
        $bookDetails = $bookModel->getBookById($bookId);
        $bookIdreplace = $bookModel->findGoogleId($bookId);
        
        if (!is_numeric($bookId)&& !$bookIdreplace) {
            $this->lookInApi($bookId);
            exit;
        }
        else if ($bookIdreplace) {
            $bookId= $bookIdreplace;
        }
        $userBookData = null;
        if ($userId) {
            $userBookData = $bookModel->getUserBookData($userId, $bookId);
             $bookDetails = $bookModel->getBookById($bookId);
        }

        $reviews = $bookModel->getReviewsByBookId($bookId);
        
        $templateData = [
            'book_id' => $bookDetails['BOOK_ID'],
            'book_title' => $bookDetails['TITLE'],
            'book_author' => $bookDetails['AUTHOR_NAME'],
            'book_translator' => $bookDetails['TRANSLATOR_NAME'] ?? 'N/A',
            'book_genre' => $bookDetails['GENRE'] ?? 'N/A',
            'book_publication_year' => $bookDetails['PUBLICATION_YEAR'] ?? 'N/A',
            'book_image_url' => $bookDetails['COVER_URL'] ?: 'assets/img/default-book.png',
            'book_description' => $bookDetails['SUMMARY'] ?? 'No description available.',
            'book_page_count' => $bookDetails['PAGES'] ?? 'N/A',
            'book_isbn' => $bookDetails['ISBN'] ?? 'N/A',
            'book_language' => $bookDetails['LANGUAGE'] ?? 'N/A',
            'book_publisher' => $bookDetails['PUBLISHING_HOUSE_NAME'] ?? 'N/A',
            'book_sub_publisher' => $bookDetails['SUB_PUBLISHER_NAME'] ?? 'N/A',
            'book_source_api' => $bookDetails['SOURCE_API'] ?? 'N/A',
            'is_owned' => $userBookData ? ($userBookData['IS_OWNED'] == 'Y') : false,
            'reading_status' => $userBookData ? $userBookData['STATUS'] : 'to-read',
            'pages_read' => $userBookData ? $userBookData['PAGES_READ'] : 0,
             'reviews' => $reviews,
            'additionalCSS' => [
                '/ShelfControl/views/css/book.css',
                '/ShelfControl/views/css/bookPage/book-info.css',
                '/ShelfControl/views/css/bookPage/reading-progress.css',
                '/ShelfControl/views/css/bookPage/similar-books.css',
                '/ShelfControl/views/css/bookPage/dark-theme-book.css',
                '/ShelfControl/views/css/bookPage/reviews.css',
                '/ShelfControl/views/css/bookPage/librarySugestions.css'

            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/book.js',
                '/ShelfControl/views/scripts/review.js'
            ]
        ];
                
        // Render the view
        $view = new BaseView();
        $view->renderTemplate('book', $templateData);
       }
    
    function extractISBN(array $industryIdentifiers): ?string {
    if (empty($industryIdentifiers)) {
        return null;
    }

    $isbn13 = null;
    $isbn10 = null;

    foreach ($industryIdentifiers as $identifier) {
        if (!isset($identifier['type']) || !isset($identifier['identifier'])) {
            continue;
        }

        if ($identifier['type'] === 'ISBN_13') {
            $isbn13 = $identifier['identifier'];
        } elseif ($identifier['type'] === 'ISBN_10') {
            $isbn10 = $identifier['identifier'];
        }
    }

    // Preferăm ISBN_13 dacă există
    if ($isbn13 !== null) {
        return $isbn13;
    }

    // Dacă nu, returnăm ISBN_10 dacă există
    if ($isbn10 !== null) {
        return $isbn10;
    }

    // Dacă nu există niciunul, returnăm null
    return null;
}

    public function saveBookApi($bookModel, $bookId,$userId) {
        $apiUrl = 'https://www.googleapis.com/books/v1/volumes/' . urlencode($bookId);
            $jsonStr = file_get_contents($apiUrl);
            $json = json_decode($jsonStr, true);

            if (!$json || !isset($json['volumeInfo'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Book not found in API']);
                exit;
            }

                    $info = $json['volumeInfo'];

                    $bookData = [
                        'title' => $info['title'] ?? null,
                        'author' => isset($info['authors'][0]) ? $info['authors'][0] : null,
                        'translator' => null,
                        'publishing_house' => $info['publisher'] ?? null,
                        'sub_publisher' => null,
                        'isbn' => $this->extractISBN($info['industryIdentifiers'] ?? []),
                        'publication_year' => isset($info['publishedDate']) ? intval(substr($info['publishedDate'], 0, 4)) : null,
                        'cover' => $info['imageLinks']['thumbnail'] ?? null,
                        'language' => $info['language'] ?? null,
                        'genre' => isset($info['categories'][0]) ? $info['categories'][0] : null,
                        'summary' => $info['description'] ?? null,
                        'pages' => $info['pageCount'] ?? null,
                        'google_id' => $bookId,
                        'source' => 'Google Books API'
                    ];
                  
                  
                  $bookTitle = $bookData['title'];

                  $jsonToImport = json_encode([$bookData]); // array cu un singur obiect (carte)
                   $result = $bookModel->importBooksFromJson($jsonToImport);
                   
                   $bookModel->insertIntoUserBook($userId, $bookModel->getBookIdByTitle($bookTitle));

                    return  $bookModel->getBookIdByTitle($bookTitle);
                          

             }
  
    
    public function updateBook() {

        if (!$this->jwt->verifyLogin()) {
           
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }
        
        $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
        $email = $decoded->data->email;
        
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        
        // Get user ID from email
        $userModel = new \App\Models\UserModel($conn);
        $userId = $userModel->getUserIdByEmail($email);
        
        if (!isset($_POST['action']) || !isset($_POST['book_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }
        
        $bookId = $_POST['book_id'];
        $action = $_POST['action'];
        $bookModel = new BookModel($conn);
        $bookIdreplace = $bookModel->findGoogleId($bookId);


        if(!is_numeric($bookId)&& !$bookIdreplace) {
             $bookId=$this->saveBookApi($bookModel, $bookId, $userId);
      
        }
        else if ($bookIdreplace) {
            $bookId = $bookIdreplace;
        }

   
        switch ($action) {
            case 'status':
                $status = $_POST['status'] ?? 'to-read';
                $result = $bookModel->updateBookStatus($userId, $bookId, $status);
                break;
                
            case 'progress':
                $pagesRead = $_POST['pages_read'] ?? 0;
                $result = $bookModel->updateBookProgress($userId, $bookId, $pagesRead);
                break;
                
            case 'owned':
                $isOwned = $_POST['is_owned'] ?? 'N';
                $result = $bookModel->updateBookOwned($userId, $bookId, $isOwned);
                break;
                
            default:
                $result = false;
        }
        
        header('Content-Type: application/json');
            echo json_encode([
                                'success' => true,
                                'book_id' => $bookId,
                                'redirect_url' =>  '/ShelfControl/book-details?id=' . $bookId
                            ]);
        exit;    
    }
    
    public function showBooksByAttribute($type, $value) {
        // Verify login
        $isLoggedIn = $this->jwt->verifyLogin();
        if (!$isLoggedIn) {
            header('Location: /ShelfControl/login');
            exit;
        }

        // Get current user ID
        $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
        $email = $decoded->data->email;
        
        require_once __DIR__ . '/../models/dbConnection.php';
        $userModel = new \App\Models\UserModel($conn);
        $userId = $userModel->getUserIdByEmail($email);
        
        // Get filtered books
        $bookModel = new BookModel($conn);
        $books = [];
        $title = "";
        
        switch ($type) {
            case 'author':
                $books = $bookModel->getBooksByAuthor($value);
                $title = "Books by $value";
                break;
            case 'publisher':
                $books = $bookModel->getBooksByPublisher($value);
                $title = "Books from $value";
                break;
            case 'translator':
                $books = $bookModel->getBooksByTranslator($value);
                $title = "Books translated by $value";
                break;
            case 'subpublisher':
                $books = $bookModel->getBooksBySubPublisher($value);
                $title = "Books from $value";
                break;
            case 'genre':
                $books = $bookModel->getBooksByGenre($value);
                $title = "Books in '$value' genre";
                break;
            case 'edition':
                $books = $bookModel->getBooksByTitle($value);
                $title = "Other editions of \"$value\"";
                break;

        }
        
        // Categorize books by status
        $ownedBooks = [];
        $toReadBooks = [];
        $readBooks = [];

        foreach ($books as $book) {
            // Get user-book relationship data
            $userBookData = $bookModel->getUserBookData($userId, $book['BOOK_ID']);
            
            if ($userBookData) {
                if ($userBookData['IS_OWNED'] == 'Y') {
                    $ownedBooks[] = $book;
                }
                
                if ($userBookData['STATUS'] == 'to-read') {
                    $toReadBooks[] = $book;
                }

                if ($userBookData['STATUS'] == 'completed') {
                    $readBooks[] = $book;
                }
            }
        }
        
        // Render the new categorized view
        $view = new \App\Views\BaseView();
        $view->renderTemplate('categorizedBooksDisplay', [
            'section_title' => $title,
            'ownedBooks' => $ownedBooks,
            'toReadBooks' => $toReadBooks,
            'readBooks' => $readBooks,
            'empty_message' => "No books found for this $type.",
            'additionalCSS' => [
                '/ShelfControl/views/css/lib.css',
                '/ShelfControl/views/css/book-section.css'
            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/stats-scroll.js'
            ]
        ]);
    }
    public function addReview() {


        $isLoggedIn = $this->jwt->verifyLogin();
        if (!$isLoggedIn) {
            header('Location: /ShelfControl/login');
            exit;
        }

        $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
        $email = $decoded->data->email;
        
        
        require_once __DIR__ . '/../models/dbConnection.php';
        $userModel = new \App\Models\UserModel($conn);
       

        $userId = $userModel->getUserIdByEmail($email);
        
        $bookId = $_POST['book_id'] ?? null;
        $reviewText = $_POST['review_text'] ?? '';
        $stars = $_POST['rating'] ?? 0;

        $bookModel = new BookModel($conn);
        $bookIdreplace = $bookModel->findGoogleId($bookId);


        if (!$bookId) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Missing book ID or review text']);
            exit;
        }


         if(!is_numeric($bookId)&& !$bookIdreplace) {
             $bookId=$this->saveBookApi($bookModel, $bookId, $userId);
      
        }
        else if ($bookIdreplace) {
            $bookId = $bookIdreplace;
        }

        ////////////////////////////aici se adauga review-ul in baza de date+stiri
      
        $result = $bookModel->addReview($userId, $bookId, $stars,$reviewText);
        $username= $userModel->getUsernameById($userId);

        if (!$result) {
            http_response_code(500); 
            echo json_encode(['error' => 'Failed to add review']);
            exit;
        }
        $newsModel = new \App\Models\NewsModel($conn);
        $newsTitle = "User <strong>$username</strong> added a new review for the book <strong>{$bookModel->getBookTitleById($bookId)}</strong>.";
        $link = "/ShelfControl/book-details?id={$bookId}";
        $newsModel->addNews('review', $newsTitle, $reviewText, $link);
        

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Review added successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to add review']);
        }
    }
    
}