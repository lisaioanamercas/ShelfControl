<?php

namespace App\Models;

class BookModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getToReadBooks($userId) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN UserBook ub ON b.book_id = ub.book_id 
                WHERE ub.user_id = :user_id AND ub.status = 'to-read'";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getOwnedBooks($userId) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN UserBook ub ON b.book_id = ub.book_id 
                WHERE ub.user_id = :user_id AND ub.is_owned = 'Y'";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }
    public function getCurrentlyReadingBooks($userId) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, b.pages, a.name as author_name,
                    ub.pages_read, ub.status
            FROM Book b 
            JOIN Author a ON b.author_id = a.author_id
            JOIN UserBook ub ON b.book_id = ub.book_id 
            WHERE ub.user_id = :user_id AND ub.status = 'currently-reading'";

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt, OCI_DEFAULT); // Using OCI_DEFAULT for fresh data
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        return $books;
    }

    public function updateReadingProgress($userId, $bookId, $pagesRead) {
        // First check if the book exists for this user
        $checkSql = "SELECT book_id FROM UserBook WHERE user_id = :user_id AND book_id = :book_id";
        $checkStmt = oci_parse($this->conn, $checkSql);
        oci_bind_by_name($checkStmt, ':user_id', $userId);
        oci_bind_by_name($checkStmt, ':book_id', $bookId);
        oci_execute($checkStmt);
        
        if (!oci_fetch_assoc($checkStmt)) {
            return false; // Book not found for this user
        }
        
        // Update the pages read
        $sql = "UPDATE UserBook 
                SET pages_read = :pages_read,
                    status = CASE 
                            WHEN :pages_read = (SELECT pages FROM Book WHERE book_id = :book_id) THEN 'completed' 
                            ELSE 'reading' 
                            END
                WHERE user_id = :user_id AND book_id = :book_id";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':pages_read', $pagesRead);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        
        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        
        return $result;
    }
}