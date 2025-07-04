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
            'additionalCSS' => [
                '/ShelfControl/views/css/lib.css',
                '/ShelfControl/views/css/book-section.css',
            ]
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

    public function allBooksLibrary() {
        require_once __DIR__ . '/../models/dbConnection.php';
        
        $bookModel = new BookModel($this->conn);
        $allBooks = $bookModel->getBooksByManualOrImport();
        
       
        $undiscoveredBooks = $bookModel->getUndiscoveredBooksByManualOrImport($this->userId);
        
        $view = new BaseView();
        $view->renderTemplate('fullLibrary', [
            'section_title' => 'Complete Book Library',
            'allBooks' => $allBooks,
            'undiscoveredBooks' => $undiscoveredBooks,
            'empty_message' => 'No books found in the database.',
            'additionalCSS' => [
                '/ShelfControl/views/css/lib.css',
                '/ShelfControl/views/css/fullLibrary.css',
                '/ShelfControl/views/css/book-section.css'

            ],
            'additionalScripts' => [
                '/ShelfControl/views/scripts/books.js',
                '/ShelfControl/views/scripts/stats-scroll.js'
            ]
        ]);
    }
    
}
