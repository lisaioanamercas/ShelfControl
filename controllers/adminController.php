<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
use App\Views\BaseView;

class AdminController {
    
    public function __construct() {
        // Check if user is logged in and is an admin
        $jwt = new BaseController();
        $isAdmin = false;
        
        if (isset($_COOKIE['jwt'])) {
            $decoded = $jwt->validateJWT($_COOKIE['jwt']);
            if ($decoded && isset($decoded->data->role) && $decoded->data->role === 'admin') {
                $isAdmin = true;
            }
        }
        
        // Redirect if not admin
        if (!$isAdmin) {
            header('Location: /ShelfControl/home');
            exit;
        }
    }
    
    // public function addBookPost() {
    //     // Process the book submission
    //     header('Content-Type: application/json');

    //     $editMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] === 'true';
    //     $bookId = $_POST['book_id'] ?? null;

        
    //     // Get the book data
    //     $title = $_POST['title'] ?? '';
    //     $author = $_POST['author'] ?? '';
    //     $translator = $_POST['translator'] ?? ''; // Add this line for translator
    //     $publication = $_POST['publication'] ?? '';
    //     $pages = $_POST['pages'] ?? '';
    //     $language = $_POST['language'] ?? '';
    //     $isbn = $_POST['isbn'] ?? '';
    //     $publisher = $_POST['publisher'] ?? '';
    //     $subpublisher = $_POST['subpublisher'] ?? '';
    //     $coverUrl = $_POST['cover_url'] ?? ''; // New field for cover URL
    //     $summary = $_POST['summary'] ?? ''; // Add this line to capture the description
    //     $genre = $_POST['genre'] ?? ''; // Add this line to capture genre

    //     //daca suntem in modul de edit !!!
    //     if ($editMode && $bookId) {
    //         // Connect to database
    //         require_once __DIR__ . '/../models/dbConnection.php';
    //         $conn = getConnection();
            
    //         // Update the book
    //         $bookModel = new BookModel($conn);
    //         $result = $bookModel->updateBook($bookId, [
    //             'title' => $title,
    //             'author' => $author,
    //             'translator' => $translator,
    //             'publication_year' => !empty($publication) ? (int)$publication : null,
    //             'pages' => !empty($pages) ? (int)$pages : null,
    //             'language' => $language,
    //             'isbn' => $isbn,
    //             'cover_url' => $coverUrl,
    //             'summary' => $summary,
    //             'genre' => $genre,
    //             'publishing_house' => $publisher,
    //             'sub_publisher' => $subpublisher
    //         ]);
            
    //         if ($result) {
    //             echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
    //         } else {
    //             echo json_encode(['success' => false, 'message' => 'Failed to update book']);
    //         }
    //         return;
    //     }

        
    //     // Convert publication year to integer if it's not empty
    //     $publicationYear = !empty($publication) ? (int)$publication : null;


    //     // Validate required fields
    //     if (empty($title) || empty($author)) {
    //         echo json_encode(['success' => false, 'message' => 'Title and author are required']);
    //         exit;
    //     }
        
    //     try {
    //         // Create book data in JSON format
    //         $bookData = json_encode([
    //             [
    //                 'title' => $title,
    //                 'author' => $author,
    //                 'translator' => $translator, // Add this line
    //                 'publication_year' => $publicationYear,
    //                 'pages' => $pages,
    //                 'language' => $language,
    //                 'isbn' => $isbn,
    //                 'publishing_house' => $publisher,
    //                 'sub_publisher' => $subpublisher,
    //                 'cover' => $coverUrl, 
    //                 'summary' => $summary,
    //                 'genre' => $genre, 
    //                 'source' => 'MANUAL'
    //             ]
    //         ]);
            
    //         // Connect to database
    //         require_once __DIR__ . '/../models/dbConnection.php';
    //         $conn = getConnection();
            
    //         // Use the BookModel to import the book
    //         $bookModel = new BookModel($conn);
    //         $result = $bookModel->importBooksFromJson($bookData);
    //         $bookId=$bookModel->getBookidByTitle($title);
            
    //         if ($result) {
                
    //              $newsModel = new \App\Models\NewsModel($conn);
    //              $newsTitle = "The book '$title' has been added.";
    //              $link = "/ShelfControl/book-details?id={$bookId}";
    //              $newsModel->addNews('New Book', $newsTitle, $summary, $bookId);
    //             echo json_encode(['success' => true, 'message' => 'Book added successfully']);
    //         } else {
    //             // Error
    //             echo json_encode(['success' => false, 'message' => 'Failed to add book']);
    //         }
    //     } catch (\Exception $e) {
    //         // Exception
    //         echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    //     }
    // }
    
    public function addBookPost() {
        // Process the book submission
        header('Content-Type: application/json');

        $editMode = isset($_POST['edit_mode']) && $_POST['edit_mode'] === 'true';
        $bookId = $_POST['book_id'] ?? null;

        // Get the book data
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

        // Validate required fields
        if (empty($title) || empty($author)) {
            echo json_encode(['success' => false, 'message' => 'Title and author are required']);
            exit;
        }

        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        $bookModel = new BookModel($conn);

        // If we're in edit mode, update the existing book
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

        // If not in edit mode, add new book
        try {
            // Convert publication year to integer if it's not empty
            $publicationYear = !empty($publication) ? (int)$publication : null;

            // Create book data in JSON format
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
            
            // Use the BookModel to import the book
            $result = $bookModel->importBooksFromJson($bookData);
            $bookId = $bookModel->getBookidByTitle($title);
            
            if ($result) {
                $newsModel = new \App\Models\NewsModel($conn);
                $newsTitle = "The book '$title' has been added.";
                $newsModel->addNews('New Book', $newsTitle, $summary, $bookId);
                echo json_encode(['success' => true, 'message' => 'Book added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add book']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function showAdminBooks() {
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        
        // Get admin-added books
        $bookModel = new BookModel($conn);
        $books = $bookModel->getBooksBySource('MANUAL');
        
        // Render the view
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
        
        // Check if book ID is provided
        if (!isset($_POST['book_id'])) {
            echo json_encode(['success' => false, 'message' => 'No book ID provided']);
            exit;
        }
        
        $bookId = $_POST['book_id'];
        
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        
        // Delete the book
        $bookModel = new BookModel($conn);
        $result = $bookModel->deleteBook($bookId);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete book']);
        }
    }

    public function getBookDetails() {
        header('Content-Type: application/json');
        
        try {
            // Check if book ID is provided
            if (!isset($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'No book ID provided']);
                exit;
            }
            
            $bookId = $_GET['id'];
            
            // Connect to database
            require_once __DIR__ . '/../models/dbConnection.php';
            $conn = getConnection();
            
            if (!$conn) {
                echo json_encode(['success' => false, 'message' => 'Database connection failed']);
                exit;
            }
            
            // Get book details
            $bookModel = new BookModel($conn);
            $book = $bookModel->getBookById($bookId);
            
            // Make sure CLOB data is properly handled
            if ($book) {
                // Handle potential CLOB objects for fields that might be CLOB
                foreach ($book as $key => $value) {
                    if (is_object($value) && method_exists($value, 'read')) {
                        $book[$key] = $value->read($value->size());
                    }
                }
                
                echo json_encode(['success' => true, 'book' => $book]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Book not found']);
            }
        } catch (\Exception $e) {
            // Log the error
            error_log("Error in getBookDetails: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function updateBook() {
        // Similar to addBookPost but update existing book
        
        header('Content-Type: application/json');
        
        // Get book ID and data
        $bookId = $_POST['book_id'] ?? '';
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
        
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        
        // Update book
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