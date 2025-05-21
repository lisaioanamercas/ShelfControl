<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
use App\Views\BaseView;

class HomeController {
    
    public function homeGet() {

        //prevent caching !!!
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        // Check if user is logged in
        $jwt = new BaseController();
        $isLoggedIn = $jwt->verifyLogin();
        
        if (!$isLoggedIn) {
            header('Location: /ShelfControl/login');
            exit;
        }
        
        // Get user data from JWT
        $decoded = $jwt->validateJWT($_COOKIE['jwt']);
        $userEmail = $decoded->data->email;
        
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        
        // Get user ID from email
        $userModel = new UserModel($conn);
        $userId = $userModel->getUserIdByEmail($userEmail);
        
        // Get books
        $bookModel = new BookModel($conn);
        $currentlyReading = $bookModel->getCurrentlyReadingBooks($userId);
        $toReadBooks = $bookModel->getToReadBooks($userId);
        $ownedBooks = $bookModel->getOwnedBooks($userId);
        
        // Render the home page with book data
        $data = [
            'currentlyReading' => $currentlyReading,
            'toReadBooks' => $toReadBooks,
            'ownedBooks' => $ownedBooks
        ];
        
        $view = new BaseView();
        $view->renderTemplate('home', $data);
    }
    public function updateProgressPost() {
        // Check if user is logged in
        $jwt = new BaseController();
        $isLoggedIn = $jwt->verifyLogin();
        
        if (!$isLoggedIn) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }
        
        // Get user ID from JWT
        $decoded = $jwt->validateJWT($_COOKIE['jwt']);
        $userEmail = $decoded->data->email;
        
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        
        // Get user ID from email
        $userModel = new UserModel($conn);
        $userId = $userModel->getUserIdByEmail($userEmail);
        
        // Validate input
        $bookId = $_POST['book_id'] ?? null;
        $pagesRead = $_POST['pages_read'] ?? null;
        
        if (!$bookId || !$pagesRead) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            exit;
        }
        
        // Update progress
        $bookModel = new BookModel($conn);
        $result = $bookModel->updateReadingProgress($userId, $bookId, $pagesRead);
        
        // Return result
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update progress']);
        }
        exit;
    }
}