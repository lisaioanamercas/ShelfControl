<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Views\BaseView;

class StatisticsController {
    private $jwt;
    
    public function __construct() {
        $this->jwt = new BaseController();
    }
    
    public function index() {
        error_log("Statistics controller index method called");
        
        // Verify login
        $isLoggedIn = $this->jwt->verifyLogin();
        if (!$isLoggedIn) {
            error_log("User not logged in, redirecting to login");
            header('Location: /ShelfControl/login');
            exit;
        }
        
        // Connect to database
        require_once __DIR__ . '/../models/dbConnection.php';
        $conn = getConnection();
        
        if (!$conn) {
            error_log("Database connection failed");
            die("Database connection failed");
        }
        
        error_log("Database connected successfully");
        
        // Get data from views
        $bookModel = new BookModel($conn);
        
        try {
            $bookStats = $bookModel->getBookPopularityStats();
            error_log("Book stats retrieved: " . count($bookStats) . " items");
            
            $userStats = $bookModel->getUserReadingStats();
            error_log("User stats retrieved: " . count($userStats) . " items");
            
            // Render the view
            $view = new BaseView();
            $view->renderTemplate('statistics', [
                'bookStats' => $bookStats,
                'userStats' => $userStats,
                'currentPage' => 'statistics',
                'additionalCSS' => [
                    '/ShelfControl/views/css/lib.css', // Add lib.css
                    '/ShelfControl/views/css/fullLibrary.css',
                    '/ShelfControl/views/css/book-section.css',
                    '/ShelfControl/views/css/sgbd/statistics.css'
                ],
                'additionalScripts' => [
                    '/ShelfControl/views/scripts/sgbd/statistics.js'
                ]
            ]);
            
            error_log("Template rendered successfully");
            
        } catch (\Exception $e) {
            error_log("Error in statistics controller: " . $e->getMessage());
            die("Error loading statistics: " . $e->getMessage());
        }
    }
    
    // public function bookPopularity() {
    //     // Verify login
    //     $isLoggedIn = $this->jwt->verifyLogin();
    //     if (!$isLoggedIn) {
    //         header('Location: /ShelfControl/login');
    //         exit;
    //     }
        
    //     require_once __DIR__ . '/../models/dbConnection.php';
    //     $conn = getConnection();
        
    //     $bookModel = new BookModel($conn);
    //     $bookStats = $bookModel->getBookPopularityStats();
        
    //     $view = new BaseView();
    //     $view->renderTemplate('bookPopularity', [
    //         'books' => $bookStats,
    //         'currentPage' => 'book-popularity',
    //         'additionalCSS' => [
    //             '/ShelfControl/views/css/sgbdstatistics.css',
    //             '/ShelfControl/views/css/book-section.css'
    //         ]
    //     ]);
    // }
}