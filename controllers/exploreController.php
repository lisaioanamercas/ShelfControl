<?php
namespace App\Controllers;
use App\Views\BaseView;

class ExploreController{


    public function exploreGet(){

        $jwt = new BaseController();
        $isLoggedIn = $jwt->verifyLogin();
        if (!$isLoggedIn) {
            header('Location: /ShelfControl/login');
            exit;
        }

        $view= new BaseView();
        $data =[];
        $view->renderTemplate('explore',$data);
    }
}