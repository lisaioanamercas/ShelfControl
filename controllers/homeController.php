<?php
namespace App\Controllers;
use App\Views\BaseView;

class HomeController{

    public function index()
    {


        require __DIR__ . '/../dbConnection.php';
       
    }

    public function homeGet(){

         $jwt = $_COOKIE['jwt'] ?? null;
        if ($jwt) {
            $baseController = new BaseController();
            $decoded = $baseController->validateJWT($jwt);
            if ($decoded) {
                $username = $decoded->data->username;
            } else {
                header('Location: /ShelfControl/login');

            }
        } else {
                           
            header('Location: /ShelfControl/login');
       }
        $view= new BaseView();
        $data = [
            'username' => 'daria'
        ];
        $view->renderTemplate('home',$data);
    }
}