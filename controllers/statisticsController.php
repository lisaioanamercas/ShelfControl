<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Views\BaseView;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class StatisticsController {
    private $jwt;
    
    public function __construct() {
        $this->jwt = new BaseController();
    }
    
    // public function index() {
    //     error_log("Statistics controller index method called");
        
    //     // Verify login
    //     $isLoggedIn = $this->jwt->verifyLogin();
    //     if (!$isLoggedIn) {
    //         error_log("User not logged in, redirecting to login");
    //         header('Location: /ShelfControl/login');
    //         exit;
    //     }
        
    //     // Connect to database
    //     require_once __DIR__ . '/../models/dbConnection.php';
    //     $conn = getConnection();
        
    //     if (!$conn) {
    //         error_log("Database connection failed");
    //         die("Database connection failed");
    //     }
        
    //     error_log("Database connected successfully");
        
    //     // Get data from views
    //     $bookModel = new BookModel($conn);
        
    //     try {
    //         $bookStats = $bookModel->getBookPopularityStats();
    //         error_log("Book stats retrieved: " . count($bookStats) . " items");
            
    //         $userStats = $bookModel->getUserReadingStats();

    //         // Debug what we're getting
    //         error_log("=== STATISTICS CONTROLLER DEBUG ===");
    //         error_log("User stats type: " . gettype($userStats));
    //         error_log("User stats count: " . (is_array($userStats) ? count($userStats) : 'NOT ARRAY'));
    //         error_log("User stats content: " . print_r($userStats, true));
            
    //         if (is_array($userStats) && !empty($userStats)) {
    //             error_log("First user type: " . gettype($userStats[0]));
    //             error_log("First user content: " . print_r($userStats[0], true));
    //         }
        


    //         error_log("User stats count: " . count($userStats));
    //         if (!empty($userStats)) {
    //             error_log("First user: " . print_r($userStats[0], true));
    //         }

    //         error_log("User stats retrieved: " . count($userStats) . " items");
            
    //         // Render the view
    //         $view = new BaseView();
    //         $view->renderTemplate('statistics', [
    //             'bookStats' => $bookStats,
    //             'userStats' => $userStats,
    //             'currentPage' => 'statistics',
    //             'additionalCSS' => [
    //                 '/ShelfControl/views/css/lib.css', // Add lib.css
    //                 '/ShelfControl/views/css/fullLibrary.css',
    //                 '/ShelfControl/views/css/book-section.css',
    //                 '/ShelfControl/views/css/sgbd/statistics.css'
    //             ],
    //             'additionalScripts' => [
    //                 '/ShelfControl/views/scripts/sgbd/statistics.js'
    //             ]
    //         ]);
            
    //         error_log("Template rendered successfully");
            
    //     } catch (\Exception $e) {
    //         error_log("Error in statistics controller: " . $e->getMessage());
    //         die("Error loading statistics: " . $e->getMessage());
    //     }
    // }

    public function index() {
        try {
            // Get database connection
            require_once __DIR__ . '/../models/dbConnection.php';
            $conn = getConnection();
            
            // Create book model
            $bookModel = new BookModel($conn);
            
            // Get statistics
            $bookStats = $bookModel->getBookPopularityStats();
            error_log("Book stats retrieved: " . count($bookStats) . " items");
            
            $userStats = $bookModel->getUserReadingStats();
            error_log("User stats retrieved: " . count($userStats) . " items");
            
            // Debug what we're actually getting
            if (!empty($userStats)) {
                error_log("First user stat: " . print_r($userStats[0], true));
            }
            
            // Render the view
            $view = new BaseView();
            $view->renderTemplate('statistics', [
                'bookStats' => $bookStats,
                'userStats' => $userStats,
                'currentPage' => 'statistics',
                'pageTitle' => 'Statistics',
                'additionalCSS' => [
                    '/ShelfControl/views/css/lib.css',
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
    
}