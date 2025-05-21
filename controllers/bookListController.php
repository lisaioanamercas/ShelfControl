<?php
namespace App\Controllers;

use App\Views\BaseView;
use App\Models\BookModel;
use App\Middleware\AuthMiddleware;

class BookListController {
    private $bookModel;
    private $view;

    public function __construct() {
        $this->view = new BaseView();
    }

    /**
     * Display to-read books page
     */
    public function toReadGet() {
        // Check authentication
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::getUserId();

        // Get database connection
        require __DIR__ . '/../models/dbConnection.php';
        
        // Initialize BookModel with connection
        $this->bookModel = new BookModel($conn);

        // Get to-read books
        $books = $this->bookModel->getBooksByStatus($userId, 'to-read');

        // Prepare data for the view
        $data = [
            'books' => $books,
            'page_title' => 'To-Read List',
            'type' => 'to-read'
        ];

        // Render the book list template
        $this->view->renderTemplate('book_list', $data);
    }

    /**
     * Display owned books page
     */
    public function ownedGet() {
        // Check authentication
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::getUserId();

        // Get database connection
        require __DIR__ . '/../models/dbConnection.php';
        
        // Initialize BookModel with connection
        $this->bookModel = new BookModel($conn);

        // Get owned books
        $books = $this->bookModel->getBooksByStatus($userId, 'owned');

        // Prepare data for the view
        $data = [
            'books' => $books,
            'page_title' => 'My Library',
            'type' => 'owned'
        ];

        // Render the book list template
        $this->view->renderTemplate('book_list', $data);
    }
}