<?php

namespace App\Controllers;

use App\Views\BaseView; 

class LandingController
{
    public function index()
    {
        $data = [
            'title' => 'Welcome to Shelf Control',
            'heading' => 'Shelf Control',
            'subheading' => 'Cause you are one book away from a book avalanche',
            'loginUrl' => '/ShelfControl/login',
            'registerUrl' => '/ShelfControl/register',
            'imagePath' => '/ShelfControl/views/resources/landingPictures.jpg',
        ];
        $view = new BaseView();
        $view->renderTemplate('landing',$data);
    }
}