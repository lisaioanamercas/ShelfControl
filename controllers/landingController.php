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
            'additionalCSS' => [
                '/ShelfControl/views/css/landing.css',
                '/ShelfControl/views/css/components/buttons.css'
            ]
        ];
        $view = new BaseView();
        $view->renderTemplate('landing', $data);
    }
}