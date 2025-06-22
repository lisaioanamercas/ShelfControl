<?php
namespace App\Controllers;

use App\Models\SocialModel;
use App\Views\BaseView;

class SocialController {
    private $jwt;
    private $userId;
    private $conn;
    
    public function __construct() {
        // Initialize the JWT handler
        $this->jwt = new BaseController();
        
        // Check login
        if (!$this->jwt->verifyLogin()) {
            header('Location: /ShelfControl/login');
            exit;
        }
        
        $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
        $email = $decoded->data->email;
        
        require_once __DIR__ . '/../models/dbConnection.php';
        $userModel = new \App\Models\UserModel($conn);
        $this->userId = $userModel->getUserIdByEmail($email);
        $this->conn = $conn;
    }
    
    public function index() {
        $socialModel = new SocialModel($this->conn);
        $groups = $socialModel->getUserGroups($this->userId);
        
        $view = new BaseView();
        $view->renderTemplate('social', [
            'groups' => $groups,
            'userId' => $this->userId,
            'currentPage' => 'social',
            'pageTitle' => 'Reading Groups',
            'additionalCSS' => [
                '/ShelfControl/views/css/social.css'
            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/social.js'
            ]
        ]);
    }
    
    public function getUserGroups() {
        $socialModel = new SocialModel($this->conn);
        $groups = $socialModel->getUserGroups($this->userId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'groups' => $groups
        ]);
    }
    
    public function createGroup() {
      
        
        $groupName = $_POST['name'] ?? '';
        
        if (empty($groupName)) {
            http_response_code(400);
            echo json_encode(['error' => 'Group name is required']);
            exit;
        }
        
        $socialModel = new SocialModel($this->conn);
        $groupId = $socialModel->createGroup($groupName, $this->userId);
        
        header('Content-Type: application/json');
        if ($groupId) {
            echo json_encode([
                'success' => true,
                'groupId' => $groupId,
                'message' => 'Group created successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create group'
            ]);
        }
    }
    
    public function addMember() {
        
        $groupId = $_POST['group_id'] ?? '';
        $userId = $_POST['user_id'] ?? '';
        
        if (empty($groupId) || empty($userId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Group ID and User ID are required']);
            exit;
        }
        
        $socialModel = new SocialModel($this->conn);
        $result = $socialModel->addGroupMember($groupId, $userId);
        
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Member added successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to add member'
            ]);
        }
    }
    
    public function startGroupReading() {
        
        $groupId = $_POST['group_id'] ?? '';
        $bookId = $_POST['book_id'] ?? '';
        
        if (empty($groupId) || empty($bookId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Group ID and Book ID are required']);
            exit;
        }
        
        $socialModel = new SocialModel($this->conn);
        $result = $socialModel->assignGroupBook($groupId, $bookId);
        
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Book assigned to group successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to assign book to group'
            ]);
        }
    }
    
    public function searchUsers() {
        error_reporting(0);
        ini_set('display_errors', 0);
        
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=UTF-8');
        
        try {
            $email = isset($_GET['email']) ? $_GET['email'] : '';
            
            if (empty($email)) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'Email search term is required'
                ]);
                exit;
            }
            

            $socialModel = new SocialModel($this->conn);
            
            $users = $socialModel->searchUsersByEmail($email);
            
            echo json_encode([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            error_log("Search users error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
            echo json_encode([
                'success' => false,
                'error' => 'Server error occurred while searching'
            ]);
        }
        exit;
    }
}