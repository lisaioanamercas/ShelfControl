<?php
namespace App\Controllers;
use App\Views\BaseView;

class HomeController{


    public function homeGet(){

        $jwt = new BaseController();
        $isLoggedIn = $jwt->verifyLogin();
        if (!$isLoggedIn) {
            header('Location: /ShelfControl/login');
            exit;
        }

        $view= new BaseView();
        $data =[];
        $view->renderTemplate('home',$data);
    }
}