<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Views\BaseView;

class UserBooksController {
    private $jwt;
    private $userId;
    private $conn;

    public function __construct() {
        $this->jwt = new \App\Controllers\BaseController();

        // Check login and setup DB
        if (!$this->jwt->verifyLogin()) {
            header('Location: /ShelfControl/login');
            exit;
        }

        $decoded = $this->jwt->validateJWT($_COOKIE['jwt']);
        $email = $decoded->data->email;

        require_once __DIR__ . '/../models/dbConnection.php';
        $this->conn = $conn;
        $userModel = new \App\Models\UserModel($this->conn);
        $this->userId = $userModel->getUserIdByEmail($email);
    }

    private function renderBooksPage(array $books, string $title, string $emptyMessage) {
        $view = new BaseView();
        $view->renderTemplate('userBooksDisplay', [
            'section_title' => $title,
            'books' => $books,
            'empty_message' => $emptyMessage,
            'showSearchForm' => true,
        ]);
    }

    public function readBooks() {
        $model = new BookModel($this->conn);
        $books = $model->getReadBooks($this->userId);
        $this->renderBooksPage($books, 'Books You Have Finished', 'You haven\'t finished any books yet.');
    }

    public function ownedBooks() {
        $model = new BookModel($this->conn);
        $books = $model->getOwnedBooks($this->userId);
        $this->renderBooksPage($books, 'Your Owned Books', 'You haven\'t added any owned books yet.');
    }

    public function toReadBooks() {
        $model = new BookModel($this->conn);
        $books = $model->getToReadBooks($this->userId);
        $this->renderBooksPage($books, 'Books You Want to Read', 'Your to-read pile is empty.');
    }

    public function currentlyReadingBooks() {
        $model = new BookModel($this->conn);
        $books = $model->getCurrentlyReadingBooks($this->userId);
        $this->renderBooksPage($books, 'Currently Reading', 'You haven\'t started any books.');
    }
}
