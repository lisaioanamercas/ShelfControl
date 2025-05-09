<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\BaseView;

class RegisterController {
    public function register() {
        require __DIR__ . '/../dbConnection.php';

        $error = ''; 
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                $error = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters long.';
            }

            if (empty($error)) {
                $userModel = new UserModel($conn);

                if ($userModel->verifyUser($email, $username)) {
                    $error = 'Email or username already exists.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    if ($userModel->createUser($email, $username, $hashedPassword)) {
                        $success = 'Registration successful! Welcome, ' . htmlspecialchars($username) . '!';
                    } else {
                        $error = 'Database error: Could not create user.';
                    }
                }
            }
        }

    

        $data = [
            'heading' => 'Register',
            'message' => !empty($error) ? "<span style='color: red;'>{$error}</span>" : (!empty($success) ? "<span style='color: green;'>{$success}</span>" : ''),
        ];

        $view = new BaseView();
        $view->renderTemplate('register',$data);
      
    }
}
