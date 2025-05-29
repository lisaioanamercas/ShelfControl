<?php
namespace App\Controllers;

use App\Views\BaseView;

class NewsController
{
    public function index()
    {
        $data = [
            'currentPage' => 'news',
            'pageTitle' => 'News',
            'additionalCSS' => [
                '/ShelfControl/views/css/news.css'
            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/news.js'
            ]
        ];
        
        $view = new BaseView();
        $view->renderTemplate('news', $data);
    }
}