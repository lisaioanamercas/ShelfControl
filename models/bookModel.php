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
    public function getBookById($bookId) {
        $sql = "SELECT b.*, 
                a.name AS author_name, 
                ph.name AS publishing_house_name,
                sp.name AS sub_publisher_name,
                t.name AS translator_name
                FROM Book b 
                LEFT JOIN Author a ON b.author_id = a.author_id
                LEFT JOIN PublishingHouse ph ON b.publishing_house_id = ph.publishing_house_id
                LEFT JOIN SubPublisher sp ON b.sub_publisher_id = sp.sub_publisher_id
                LEFT JOIN Translator t ON b.translator_id = t.translator_id
                WHERE b.book_id = :book_id";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_execute($stmt);
        
        $row = oci_fetch_assoc($stmt);
        
        // Properly handle CLOB data
        if ($row && isset($row['SUMMARY']) && is_object($row['SUMMARY'])) {
            $row['SUMMARY'] = $row['SUMMARY']->read($row['SUMMARY']->size());
        }
    
        return $row;
    }

    public function getUserBookData($userId, $bookId) {
        $sql = "SELECT * FROM UserBook 
                WHERE user_id = :user_id 
                AND book_id = :book_id";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_execute($stmt);
        
        $row = oci_fetch_assoc($stmt);
        return $row;
    }

    // Methods needed for book status updates
    public function updateBookStatus($userId, $bookId, $status) {
        // Check if record exists
        $existingRecord = $this->getUserBookData($userId, $bookId);
        
        if ($existingRecord) {
            $sql = "UPDATE UserBook 
                    SET status = :status 
                    WHERE user_id = :user_id 
                    AND book_id = :book_id";
        } else {
            $sql = "INSERT INTO UserBook (user_id, book_id, status) 
                    VALUES (:user_id, :book_id, :status)";
        }
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':status', $status);
        
        return oci_execute($stmt);
    }

    public function updateBookProgress($userId, $bookId, $pagesRead) {
        // Check if record exists
        $existingRecord = $this->getUserBookData($userId, $bookId);
        
        if ($existingRecord) {
            $sql = "UPDATE UserBook 
                    SET pages_read = :pages_read 
                    WHERE user_id = :user_id 
                    AND book_id = :book_id";
        } else {
            $sql = "INSERT INTO UserBook (user_id, book_id, pages_read, status) 
                    VALUES (:user_id, :book_id, :pages_read, 'reading')";
        }
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':pages_read', $pagesRead);
        
        return oci_execute($stmt);
    }

    public function updateBookOwned($userId, $bookId, $isOwned) {
        // Check if record exists
        $existingRecord = $this->getUserBookData($userId, $bookId);
        
        if ($existingRecord) {
            $sql = "UPDATE UserBook 
                    SET is_owned = :is_owned 
                    WHERE user_id = :user_id 
                    AND book_id = :book_id";
        } else {
            $sql = "INSERT INTO UserBook (user_id, book_id, is_owned, status) 
                    VALUES (:user_id, :book_id, :is_owned, 'to-read')";
        }
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':is_owned', $isOwned);
        
        return oci_execute($stmt);
    }

    public function searchBooks($query) {
    // Clean the query to prevent SQL injection
    $searchTerm = '%' . strtoupper($query) . '%';
    
    $sql = "SELECT DISTINCT b.book_id, b.title, b.cover_url, b.publication_year, b.genre,
                  a.name as author_name, ph.name as publisher_name
           FROM Book b
           LEFT JOIN Author a ON b.author_id = a.author_id
           LEFT JOIN PublishingHouse ph ON b.publishing_house_id = ph.publishing_house_id
           LEFT JOIN SubPublisher sp ON b.sub_publisher_id = sp.sub_publisher_id
           LEFT JOIN Translator t ON b.translator_id = t.translator_id
           WHERE UPPER(b.title) LIKE :query
              OR UPPER(a.name) LIKE :query
              OR UPPER(ph.name) LIKE :query
              OR UPPER(sp.name) LIKE :query
              OR UPPER(b.genre) LIKE :query
              OR UPPER(t.name) LIKE :query
              OR UPPER(b.isbn) LIKE :query
           ORDER BY b.title";
           
    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':query', $searchTerm);
    oci_execute($stmt);
    
    $books = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $books[] = $row;
    }
    
    return $books;
}
 public function importBooksFromJson(string $jsonData): bool {
    $sql = "BEGIN import_book_direct(:v_json_data); END;";
    $stmt = oci_parse($this->conn, $sql);
    if (!$stmt) {
        $e = oci_error($this->conn);
        throw new Exception("Eroare la pregătirea procedurii: " . $e['message']);
    }

    $clob = oci_new_descriptor($this->conn, OCI_D_LOB);
    oci_bind_by_name($stmt, ":v_json_data", $clob, -1, OCI_B_CLOB);

    if (!$clob->writeTemporary($jsonData, OCI_TEMP_CLOB)) {
        throw new Exception("Eroare la scrierea datelor JSON în CLOB.");
    }

    $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    if (!$result) {
        $e = oci_error($stmt);
        throw new Exception("Eroare la execuția procedurii: " . $e['message']);
    }

    oci_free_statement($stmt);
    $clob->free();

    return true;
}
    public function getBookidByTitle($title) {
        $sql = "SELECT book_id FROM Book WHERE title = :title";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':title', $title);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row ? $row['BOOK_ID'] : null;
    }

    public function insertIntoUserBook($userId, $bookId) {
        $sql = "INSERT INTO UserBook (user_id, book_id,status) VALUES (:user_id, :book_id,:status)";
        $status = 'to-read'; // Default status
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':status', $status);
        return oci_execute($stmt);
    }

    public function isBookInUserList($userId, $bookId) {
        $sql = "SELECT COUNT(*) as count FROM UserBook WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row['COUNT'] > 0;
    }


 
}