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
        $publication = $_POST['publication'] ?? '';
        $pages = $_POST['pages'] ?? '';
        $language = $_POST['language'] ?? '';
        $isbn = $_POST['isbn'] ?? '';
        $publisher = $_POST['publisher'] ?? '';
        $subpublisher = $_POST['subpublisher'] ?? '';
        $coverUrl = $_POST['cover_url'] ?? ''; // New field for cover URL

        
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
                    'publication_year' => $publication,
                    'pages' => $pages,
                    'language' => $language,
                    'isbn' => $isbn,
                    'publisher' => $publisher,
                    'subpublisher' => $subpublisher,
                    'cover' => $coverUrl, // Add cover URL here
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
    
    // Handle file upload for book cover
    // public function uploadCover() {
    //     header('Content-Type: application/json');
        
    //     // Check if file was uploaded
    //     if (!isset($_FILES['cover']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
    //         echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    //         exit;
    //     }
        
    //     // Define allowed file types and max size
    //     $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    //     $maxSize = 5 * 1024 * 1024; // 5MB
        
    //     // Validate file
    //     $file = $_FILES['cover'];
        
    //     if (!in_array($file['type'], $allowedTypes)) {
    //         echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG and PNG are allowed']);
    //         exit;
    //     }
        
    //     if ($file['size'] > $maxSize) {
    //         echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB']);
    //         exit;
    //     }
        
    //     // Generate a unique filename
    //     $filename = uniqid() . '_' . $file['name'];
    //     $uploadDir = __DIR__ . '/../uploads/covers/';
        
    //     // Create directory if it doesn't exist
    //     if (!file_exists($uploadDir)) {
    //         mkdir($uploadDir, 0777, true);
    //     }
        
    //     $uploadPath = $uploadDir . $filename;
        
    //     // Move the file
    //     if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    //         // Return the path for saving in the database
    //         echo json_encode([
    //             'success' => true, 
    //             'file' => '/ShelfControl/uploads/covers/' . $filename
    //         ]);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    //     }
    // }
}