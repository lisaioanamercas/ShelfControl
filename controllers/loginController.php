<?php

namespace App\Controllers;

use App\Models\UserModel; 
use App\Views\BaseView;
use App\Controllers\BaseController;

class LoginController
{
    private $error;
    private $success;
    private $view;

    private $jwt;

    public function __construct()
    {
        $this->error = '';
        $this->success = '';
        $this->view = new BaseView();
        $this->jwt = new BaseController();

    }    public function loginPost()
    {
        require __DIR__ . '/../models/dbConnection.php';

        // Sanitize and validate input
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = $_POST['password'] ?? '';

        // Input validation
        if (empty($email) || empty($password)) {
            $this->error = 'All fields are required.';
        }
        
        // Email validation
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error = 'Invalid email format.';
        }
        
        // Password length validation
        if (!empty($password) && strlen($password) > 255) {
            $this->error = 'Password too long.';
        }

        if (empty($this->error)) {
            $userModel = new UserModel($conn);

            if ($userModel->loginUser($email, $password) == 0) {
                $this->error = 'Account not found or password incorrect.';
            } else {
                $this->success = 'Login successful!';
                $user = $userModel->getUserIdByEmail($email);
                $role = $userModel->getUserRoleByEmail($email);
                $token = $this->jwt->generateJWT($email,$user,$role);

                setcookie('jwt', $token, time() + 3600, '/', '', false, true);

                header('Location: /ShelfControl/home');
                exit;
            }
        }

        $data = [
            'heading' => 'Login',
            'message' => !empty($this->error) ? "<span style='color: red;'>{$this->error}</span>" : '',
            'additionalCSS' => [
                '/ShelfControl/views/css/login.css'
            ]
        ];

        $this->view->renderTemplate('login', $data);
    }

    public function loginGet()
    {
        if($this->jwt->verifyLogin()){
            header('Location: /ShelfControl/home');
            exit;
        }

        $data = [
            'heading' => 'Login',
            'message' => '',
            'additionalCSS' => [
                '/ShelfControl/views/css/login.css'
            ]
        ];

        $this->view->renderTemplate('login', $data);
    }

    public function logout()
    {
     
        if (isset($_COOKIE['jwt'])) {
            try {
                $jwt = new BaseController();
                $decoded = $jwt->validateJWT($_COOKIE['jwt']);
                
                if ($decoded && isset($decoded->data->email)) {
                    require_once __DIR__ . '/../models/dbConnection.php';
                    $userModel = new \App\Models\UserModel($conn);
                    
                 
                    if ($userModel->isDemoUser($decoded->data->email)) {
                        $userId = $userModel->getUserIdByEmail($decoded->data->email);
                        
                      
                        $userModel->deleteDemoUser($userId);
                        
                        error_log("Demo user deleted on logout: " . $decoded->data->email);
                    }
                }
            } catch (\Exception $e) {
                error_log("Error handling demo user logout: " . $e->getMessage());
             
            }
        }
     
        setcookie('jwt', '', time() - 3600, '/');

        header('Location: /ShelfControl/');
        exit;
    }
}
