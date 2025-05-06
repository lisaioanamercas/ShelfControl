<?php

namespace App\Controllers;

class RegisterController{
 
    public function register(){
        require __DIR__ . '/../views/register.php';

        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
        }

        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        }
        elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } 
        elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        }

        if (empty($error)) {
            echo '<div style="color: green; text-align: center;">Registration successful! Welcome, ' . htmlspecialchars($username) . '!</div>';
        } else {
            echo '<div style="color: red; text-align: center;">' . htmlspecialchars($error) . '</div>';
        }
    }
}
