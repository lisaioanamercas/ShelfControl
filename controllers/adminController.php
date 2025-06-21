<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
use App\Views\BaseView;

class AdminController {
    
    public function __construct() {
        $jwt = new BaseController();
        $isAdmin = false;
        
        if (isset($_COOKIE['jwt'])) {
            $decoded = $jwt->validateJWT($_COOKIE['jwt']);
            if ($decoded && isset($decoded->data->role) && $decoded->data->role === 'admin') {
                $isAdmin = true;
            }
        }
        
        if (!$isAdmin) {
            header('Location: /ShelfControl/home');
            exit;
        }
    }
    
    public function addBookPost() {
        header('Content-Type: application/json');

        $editMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] === 'true';
        $bookId = $_POST['book_id'] ?? null;

        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $translator = $_POST['translator'] ?? '';
        $publication = $_POST['publication'] ?? '';
        $pages = $_POST['pages'] ?? '';
        $language = $_POST['language'] ?? '';
        $isbn = $_POST['isbn'] ?? '';
        $publisher = $_POST['publisher'] ?? '';
        $subpublisher = $_POST['subpublisher'] ?? '';
        $coverUrl = $_POST['cover_url'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $genre = $_POST['genre'] ?? '';

        if (empty($title) || empty($author)) {
            echo json_encode(['success' => false, 'message' => 'Title and author are required']);
            exit;
        }

        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        $bookModel = new BookModel($conn);

        if ($editMode && $bookId) {
            try {
                $result = $bookModel->updateBook($bookId, [
                    'title' => $title,
                    'author' => $author,
                    'translator' => $translator,
                    'publication_year' => !empty($publication) ? (int)$publication : null,
                    'pages' => !empty($pages) ? (int)$pages : null,
                    'language' => $language,
                    'isbn' => $isbn,
                    'cover_url' => $coverUrl,
                    'summary' => $summary,
                    'genre' => $genre,
                    'publishing_house' => $publisher,
                    'sub_publisher' => $subpublisher
                ]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update book']);
                }
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            return;
        }

        try {
            $publicationYear = !empty($publication) ? (int)$publication : null;

            $bookData = json_encode([
                [
                    'title' => $title,
                    'author' => $author,
                    'translator' => $translator,
                    'publication_year' => $publicationYear,
                    'pages' => $pages,
                    'language' => $language,
                    'isbn' => $isbn,
                    'publishing_house' => $publisher,
                    'sub_publisher' => $subpublisher,
                    'cover' => $coverUrl, 
                    'summary' => $summary,
                    'genre' => $genre, 
                    'source' => 'MANUAL'
                ]
            ]);
            
            $result = $bookModel->importBooksFromJson($bookData);
            $bookId = $bookModel->getBookidByTitle($title);
            
            if ($result) {
                $newsModel = new \App\Models\NewsModel($conn);
                $newsTitle = "The book '$title' has been added.";
                $link = "/ShelfControl/book-details?id={$bookId}";
                $newsModel->addNews('Book Launch', $newsTitle, $summary, $link);
                echo json_encode(['success' => true, 'message' => 'Book added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add book']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showAdminBooks() {
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        $bookModel = new BookModel($conn);
        $books = $bookModel->getBooksBySource('MANUAL');
        $view = new BaseView();
        $view->renderTemplate('userBooksDisplay', [
            'section_title' => 'Admin-Added Books',
            'books' => $books,
            'empty_message' => 'No books have been added by the admin yet.',
            'currentPage' => 'admin-books',
            'additionalCSS' => [
                '/ShelfControl/views/css/lib.css',
                '/ShelfControl/views/css/book-section.css',
                '/ShelfControl/views/css/admin/admin.css'
            ]
        ]);
    }

    public function deleteBook() {
       header('Content-Type: application/json');
         $data = [];
         parse_str(file_get_contents("php://input"), $data);

        if (!isset($data['book_id'])) {
        echo json_encode(['success' => false, 'message' => 'No book ID provided']);
        exit;
    }
          $bookId = $data['book_id'];
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        $bookModel = new BookModel($conn);
        $result = $bookModel->deleteBook($bookId);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete book']);
        }
    }

    public function getBookDetails() {
        while (ob_get_level()) {
            ob_end_clean();
        }
        error_log("getBookDetails called");
        header('Content-Type: application/json; charset=UTF-8');
        try {
            if (!isset($_GET['id'])) {
                error_log("No book ID provided in GET parameters");
                throw new \Exception('No book ID provided');
            }
            $bookId = $_GET['id'];
            error_log("Getting book details for ID: " . $bookId);
            require_once __DIR__ . '/../models/dbConnection.php';
            $conn = getConnection();
            if (!$conn) {
                error_log("Database connection failed");
                throw new \Exception('Database connection failed');
            }
            $bookModel = new BookModel($conn);
            $book = $bookModel->getBookById($bookId);
            error_log("Book data retrieved: " . ($book ? "YES" : "NO"));
            if (!$book) {
                error_log("Book not found with ID: " . $bookId);
                throw new \Exception('Book not found with ID: ' . $bookId);
            }
            foreach ($book as $key => $value) {
                if (is_object($value) && method_exists($value, 'read')) {
                    try {
                        $book[$key] = $value->read($value->size());
                    } catch (\Exception $e) {
                        error_log("Error reading CLOB field $key: " . $e->getMessage());
                        $book[$key] = '';
                    }
                }
                if (!is_scalar($value) && !is_null($value)) {
                    $book[$key] = (string)$value;
                }
            }
            $jsonTest = json_encode($book);
            if ($jsonTest === false) {
                error_log("JSON encoding failed: " . json_last_error_msg());
                throw new \Exception('JSON encoding failed: ' . json_last_error_msg());
            }
            $response = ['success' => true, 'book' => $book];
            error_log("About to send response: " . json_encode($response));
            echo json_encode($response);
        } catch (\Exception $e) {
            error_log("Error in getBookDetails: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        error_log("getBookDetails function ending");
        exit;
    }

    public function updateBook() {
        header('Content-Type: application/json');
        $data = [];
        parse_str(file_get_contents("php://input"), $data);

        $bookId = $data['book_id'] ?? '';
        $title = $data['title'] ?? '';
        $author = $data['author'] ?? '';
        $translator = $data['translator'] ?? '';
        $publication = $data['publication'] ?? '';
        $pages = $data['pages'] ?? '';
        $language = $data['language'] ?? '';
        $isbn = $data['isbn'] ?? '';
        $publisher = $data['publisher'] ?? '';
        $subpublisher = $data['subpublisher'] ?? '';
        $coverUrl = $data['cover_url'] ?? '';
        $summary = $data['summary'] ?? '';
        $genre = $data['genre'] ?? '';

        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        $bookModel = new BookModel($conn);
        $result = $bookModel->updateBook($bookId, [
            'title' => $title,
            'author' => $author,
            'translator' => $translator,
            'publication_year' => !empty($publication) ? (int)$publication : null,
            'pages' => !empty($pages) ? (int)$pages : null,
            'language' => $language,
            'isbn' => $isbn,
            'cover_url' => $coverUrl,
            'summary' => $summary,
            'genre' => $genre,
            'publishing_house' => $publisher,
            'sub_publisher' => $subpublisher
        ]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update book']);
        }
    }
}