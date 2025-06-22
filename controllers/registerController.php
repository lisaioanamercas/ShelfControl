<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\BaseView;

class RegisterController {

    
      function removeDiacritics($string) {
        $map = [
            'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ț' => 't',
            'Ă' => 'A', 'Â' => 'A', 'Î' => 'I', 'Ș' => 'S', 'Ț' => 'T'
        ];
        return strtr($string, $map);
   }
    public function registerPost() {

        require __DIR__ . '/../models/dbConnection.php';

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $city = $_POST['city'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)|| empty($city)) {
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

            if ($userModel->verifyUser($username, $email)) {
                $error = 'Email or username already exists.';
            } else {
                $token = "91f98b6e3ecd27";
                $api_url = "https://ipinfo.io/json?token={$token}";

                $geo_info = file_get_contents($api_url);
                $location = json_decode($geo_info, true);

                $city = $location['city'] ?? "Locație necunoscută";

                
                    if (!empty($city)) {
                        $city = $this->removeDiacritics($city);
                    }

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                if ($userModel->createUser($email, $username, $hashedPassword, $city)) {
                    // PARTE DE SGBD !!!! pentru user ul gigi.becali@gmail.com cu parola cheese
                    if ($userModel->isDemoUser($email)) {
                        try {
                            $userId = $userModel->getUserIdByEmail($email);
                            
                         
                            $userModel->callPopulateDemoData($userId);
                            
                            error_log("Demo data populated for user: " . $email);
                        } catch (\Exception $e) {
                            error_log("Error populating demo data: " . $e->getMessage());
                      
                        }
                    }
                      header('Location: /ShelfControl/home');

                } else {
                    $error = 'Database error: Could not create user.';
                }
            }
        }
         $data = [
        'heading' => 'Register',
        'message' => !empty($error) ? "<span style='color: red;'>{$error}</span>" : '',
        'additionalCSS' => [
            '/ShelfControl/views/css/register.css'
        ]
       ];

        $view = new BaseView();
       $view->renderTemplate('register', $data);
    }

    public function registerGet() {

        if (isset($_COOKIE['jwt'])) {
            $baseController = new BaseController();
            $decoded = $baseController->validateJWT($_COOKIE['jwt']);
            if ($decoded) {
                header('Location: /ShelfControl/home');
                exit;
            }
        }

        $data = [
            'heading' => 'Register',
            'message' => '',
            'additionalCSS' => [
                '/ShelfControl/views/css/register.css'
            ]
        ];

        $view = new BaseView();
        $view->renderTemplate('register', $data);
    }
  
}

