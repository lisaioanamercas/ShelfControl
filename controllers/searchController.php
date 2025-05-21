<?php
namespace App\Controllers;

use App\Models\BookModel;

class SearchController {
    private $jwt;
    
    public function __construct() {
        $this->jwt = new BaseController();
    }
    
    public function search() {
        // Get database connection
        require_once __DIR__ . '/../models/dbConnection.php';
        
        // Get search query
        $query = $_GET['query'] ?? '';
        
        if (empty($query)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No search query provided']);
            exit;
        }
        
        // Get user info if logged in
        $userId = null;
        if ($this->jwt->verifyLogin()) {
            $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
            $email = $decoded->data->email;
            $userModel = new \App\Models\UserModel($conn);
            $userId = $userModel->getUserIdByEmail($email);
        }
        
        // Search books
        $bookModel = new BookModel($conn);
        $results = $bookModel->searchBooks($query);
        
        // Add user-specific data to results if logged in
        if ($userId) {
            foreach ($results as &$book) {
                $userBookData = $bookModel->getUserBookData($userId, $book['BOOK_ID']);
                $book['is_owned'] = $userBookData && $userBookData['IS_OWNED'] === 'Y';
                $book['reading_status'] = $userBookData ? $userBookData['STATUS'] : null;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'results' => $results]);
        exit;
    }
}