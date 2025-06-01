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
    
    public function addBookPost() {
        // Process the book submission
        header('Content-Type: application/json');
        
        // Get the book data
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $translator = $_POST['translator'] ?? ''; // Add this line for translator
        $publication = $_POST['publication'] ?? '';
        $pages = $_POST['pages'] ?? '';
        $language = $_POST['language'] ?? '';
        $isbn = $_POST['isbn'] ?? '';
        $publisher = $_POST['publisher'] ?? '';
        $subpublisher = $_POST['subpublisher'] ?? '';
        $coverUrl = $_POST['cover_url'] ?? ''; // New field for cover URL
        $summary = $_POST['summary'] ?? ''; // Add this line to capture the description
        $genre = $_POST['genre'] ?? ''; // Add this line to capture genre

        
        // Convert publication year to integer if it's not empty
        $publicationYear = !empty($publication) ? (int)$publication : null;


        // Validate required fields
        if (empty($title) || empty($author)) {
            echo json_encode(['success' => false, 'message' => 'Title and author are required']);
            exit;
        }
        
        try {
            // Create book data in JSON format
            $bookData = json_encode([
                [
                    'title' => $title,
                    'author' => $author,
                    'translator' => $translator, // Add this line
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
            
            // Connect to database
            require_once __DIR__ . '/../models/dbConnection.php';
            $conn = getConnection();
            
            // Use the BookModel to import the book
            $bookModel = new BookModel($conn);
            $result = $bookModel->importBooksFromJson($bookData);
            
            if ($result) {
                // Success
                echo json_encode(['success' => true, 'message' => 'Book added successfully']);
            } else {
                // Error
                echo json_encode(['success' => false, 'message' => 'Failed to add book']);
            }
        } catch (\Exception $e) {
            // Exception
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
                '/ShelfControl/views/css/lib.css'
            ]
        ]);
    }
}