<?php
namespace App\Controllers;

class BookController{

    public function bookGet(){


        

          if (isset($_GET['id'])) {
            $bookId = $_GET['id'];
            $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . $bookId;

            $response = file_get_contents($apiUrl);
            if ($response) {



                header('Content-Type: text/html');
                $data=json_decode($response, true);
                $bookDetails = [
                    'book_title' => $data['volumeInfo']['title'] ?? 'N/A',
                    'book_author' => implode(', ', $data['volumeInfo']['authors'] ?? []),
                    'book_genre' => implode(', ', $data['volumeInfo']['categories'] ?? []),
                    'book_publication_year' => $data['volumeInfo']['publishedDate'] ?? 'N/A',
                    'book_image_url' => $data['volumeInfo']['imageLinks']['thumbnail'] ?? 'N/A',
                    'book_description' => $data['volumeInfo']['description'] ?? 'N/A',
                    'book_page_count' => $data['volumeInfo']['pageCount'] ?? 'N/A',
                    'book_isbn' => $data['volumeInfo']['industryIdentifiers'][0]['identifier'] ?? 'N/A',
                    'book_language' => $data['volumeInfo']['language'] ?? 'N/A',
            
                ];

                $view = new \App\Views\BaseView();
                $view->renderTemplate('book', $bookDetails);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Book not found"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No book ID provided"]);
        }
    }
    public function test(){
         

          if (isset($_GET['id'])) {
            $bookId = $_GET['id'];
            $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . $bookId;

            $response = file_get_contents($apiUrl);
            if ($response) {
                header('Content-Type: application/json');
                echo $response;
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Book not found"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No book ID provided"]);
        }
    
    }
}

