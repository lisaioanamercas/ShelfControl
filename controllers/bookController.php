<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Views\BaseView;

class BookController{
    private $jwt;

    public function __construct() {
        $this->jwt = new BaseController();
    }

    public function bookGet(){
          if (isset($_GET['id'])) {
            $bookId = $_GET['id'];
            $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . $bookId;

            $response = file_get_contents($apiUrl);
            if ($response) {



                header('Content-Type: text/html');
                $data=json_decode($response, true);
                $bookDetails = [
                    'book_title' => $data['volumeInfo']['title'] ?? 'N/A',
                    'book_author' => implode(', ', $data['volumeInfo']['authors'] ?? []),
                    'book_genre' => implode(', ', $data['volumeInfo']['categories'] ?? []),
                    'book_publication_year' => $data['volumeInfo']['publishedDate'] ?? 'N/A',
                    'book_image_url' => $data['volumeInfo']['imageLinks']['thumbnail'] ?? 'N/A',
                    'book_description' => $data['volumeInfo']['description'] ?? 'N/A',
                    'book_page_count' => $data['volumeInfo']['pageCount'] ?? 'N/A',
                    'book_isbn' => $data['volumeInfo']['industryIdentifiers'][0]['identifier'] ?? 'N/A',
                    'book_language' => $data['volumeInfo']['language'] ?? 'N/A',
                ];

                $view = new \App\Views\BaseView();
                $view->renderTemplate('book', $bookDetails);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Book not found"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No book ID provided"]);
        }
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
            
            // Connect to database
            require_once __DIR__ . '/../models/dbConnection.php';
            
            // Get user ID from email
            $userModel = new \App\Models\UserModel($conn);
            $userId = $userModel->getUserIdByEmail($email);
        }
        
        // Get book details from database
        $bookModel = new BookModel($conn);
        $bookDetails = $bookModel->getBookById($bookId);
        
        if (!$bookDetails) {
            // Book not found
            header('Location: /ShelfControl/home');
            exit;
        }
        
        // Get user-specific book data if logged in
        $userBookData = null;
        if ($userId) {
            $userBookData = $bookModel->getUserBookData($userId, $bookId);
        }
        
        // Format data for template
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
            'additionalCSS' => [
                '/ShelfControl/views/css/book.css',
                '/ShelfControl/views/css/bookPage/book-info.css',
                '/ShelfControl/views/css/bookPage/reading-progress.css',
                '/ShelfControl/views/css/bookPage/similar-books.css',
                '/ShelfControl/views/css/bookPage/dark-theme-book.css'
            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/book.js'
            ]
        ];
                
        // Render the view
        $view = new BaseView();
        $view->renderTemplate('book', $templateData);
    }
    
    // New method for AJAX updates
    public function updateBook() {
        // Check if user is logged in
        if (!$this->jwt->verifyLogin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }
        
        // Get user ID from JWT
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
        echo json_encode(['success' => $result]);
    }
}

