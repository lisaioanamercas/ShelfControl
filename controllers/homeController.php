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
        $toReadBooks = $bookModel->getToReadBooksLimited($userId, 7);
        $ownedBooks = $bookModel->getOwnedBooksLimited($userId, 7);
        $readBooks = $bookModel->getReadBooksLimited($userId, 7);

        // Render the home page with book data
        $data = [
            'currentlyReading' => $currentlyReading,
            'toReadBooks' => $toReadBooks,
            'ownedBooks' => $ownedBooks,
            'readBooks' => $readBooks,
            'additionalCSS' => [
            '/ShelfControl/views/css/home.css',
            '/ShelfControl/views/css/book-section.css',
            '/ShelfControl/views/css/progress.css'
            ]
        ];
        
        $view = new BaseView();
        $view->renderTemplate('home', $data);
    }
    public function HomePost(){
        // Handle POST requests for the home page
    }
}