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

    public function getNews()
    {
         require_once __DIR__ . '/../models/newsModel.php';
         require_once __DIR__ . '/../models/dbConnection.php';

            $newsModel = new \App\Models\NewsModel($conn);
            $newsItems = $newsModel->getAllNews();
       
      
            header('Content-Type: application/rss+xml; charset=UTF-8');
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
            echo "<rss version=\"2.0\"><channel>";
            echo "<title>ShelfControl News</title>";
            echo "<link>https://localhost/ShelfControl/explore</link>";
            echo "<description>Ultimele știri din ShelfControl</description>";

            foreach ($newsItems as $item) {
                echo "<item>";
                echo "<guid>{$item['FEED_ID']}</guid>";
                echo "<type>{$item['TYPE']}</type>";
                echo "<title><![CDATA[{$item['TITLE']}]]></title>";
                $content = $item['CONTENT']?? '';
                if (is_object($content) && method_exists($content, 'load')) {
                    $content = $content->load();
                }
                
                echo "<description><![CDATA[{$content}]]></description>";
                echo "<link>{$item['RELATED_LINK']}</link>";
                echo "<pubDate>" . date(DATE_RSS, strtotime($item['PUBLISHED_AT'])) . "</pubDate>";
                echo "</item>";
            }

            echo "</channel></rss>";
            exit;
    }    

    public function addNewsPost()
    {
           $data = json_decode(file_get_contents('php://input'), true);

            $type = $data['type'] ?? '';
            $title = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            $author = $data['author'] ?? '';
            $link = $data['link'] ?? null;

            require_once __DIR__ . '/../models/dbConnection.php';
            $newsModel = new \App\Models\NewsModel($conn);

            $result = $newsModel->addNews($type, $title, $description, $link);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add news']);
            }
            exit;
    }
}