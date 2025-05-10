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

    public function __construct()
    {
        $this->error = '';
        $this->success = '';
        $this->view = new BaseView();
    }

    public function loginPost()
    {
        require __DIR__ . '/../dbConnection.php';

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->error = 'All fields are required.';
        }

        if (empty($this->error)) {
            $userModel = new UserModel($conn);

            if ($userModel->loginUser($email, $password) == 0) {
                $this->error = 'Account not found or password incorrect.';
            } else {
                $this->success = 'Login successful!';
                $jwt = new BaseController();
                $token = $jwt->generateJWT($email);

                setcookie('jwt', $token, time() + 3600, '/', '', false, true);

                header('Location: /ShelfControl/home');
                exit;
            }
        }

        $data = [
            'heading' => 'Login',
            'message' => !empty($this->error) ? "<span style='color: red;'>{$this->error}</span>" : '',
        ];

        $this->view->renderTemplate('login', $data);
    }

    public function loginGet()
    {
        if (isset($_COOKIE['jwt'])) {
            $baseController = new BaseController();
            $decoded = $baseController->validateJWT($_COOKIE['jwt']);

            if ($decoded) {
                header('Location: /ShelfControl/home');
                exit;
            }
        }

        $data = [
            'heading' => 'Login',
            'message' => '',
        ];

        $this->view->renderTemplate('login', $data);
    }

    public function logout()
    {
     
        setcookie('jwt', '', time() - 3600, '/');

        header('Location: /ShelfControl/');
        exit;
    }
}
