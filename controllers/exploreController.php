<?php
namespace App\Controllers;
use App\Views\BaseView;
use App\Models\BookModel;
use App\Models\UserModel;

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
   public function explorePost() {

    header('Content-Type: application/json');

     
    $jsonInput = file_get_contents('php://input');

   
    
    if (!$jsonInput) {
        http_response_code(400);
        echo json_encode(['error' => 'No data received']);
        exit;
    }
   

   
    require __DIR__ . '/../models/dbConnection.php';

    $bookModel = new BookModel($conn);
    $jwt = new BaseController();

    $decoded = $jwt->validateJWT($_COOKIE['jwt']);
    $userEmail = $decoded->data->email;
    $userModel = new UserModel($conn);
    $userId = $userModel->getUserIdByEmail($userEmail);
  

    $data = json_decode($jsonInput, true);
    $book= $data[0];

    $title = $book['title'];

     if($bookModel->getBookidByTitle($title)!=null)
    {
            echo json_encode(['message' => 'Cartea exista in to read deja!']);
            exit;

    }
    else{

    $bookModel->importBooksFromJson($jsonInput);

    $bookId = $bookModel->getBookIdByTitle($title);
    $bookModel->insertIntoUserBook($userId, $bookId);
    
    echo json_encode(['message' => 'Carte salvată cu succes!']);
    exit;
   }
    
}
}




