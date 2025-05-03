<?php 
 
 namespace App\Controllers;


class AuthController{

    public function login()
    {
        require __DIR__ . '/../views/login.php';
    }
}
