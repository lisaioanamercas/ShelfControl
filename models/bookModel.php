<?php

namespace App\Models;
use Exception;

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

    public function getReadBooks($userId) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN UserBook ub ON b.book_id = ub.book_id 
                WHERE ub.user_id = :user_id AND ub.status = 'completed'
                ORDER BY b.title ASC";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }



    // aici limitez cartile care o sa imi apara la sectiunile din homepage !!!
    public function getToReadBooksLimited($userId, $limit) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN UserBook ub ON b.book_id = ub.book_id 
                WHERE ub.user_id = :user_id AND ub.status = 'to-read'
                AND ROWNUM <= :limit";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':limit', $limit);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getOwnedBooksLimited($userId, $limit) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN UserBook ub ON b.book_id = ub.book_id 
                WHERE ub.user_id = :user_id AND ub.is_owned = 'Y'
                AND ROWNUM <= :limit";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':limit', $limit);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getReadBooksLimited($userId, $limit) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN UserBook ub ON b.book_id = ub.book_id 
                WHERE ub.user_id = :user_id AND ub.status = 'completed'
                ORDER BY b.title ASC
                FETCH FIRST :limit ROWS ONLY";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':limit', $limit);
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
            WHERE ub.user_id = :user_id AND ub.status = 'reading'";

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
        if (!is_numeric($bookId)) {
        // Return null or throw an exception for invalid book IDs
        return null;
        }
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

   public function  searchBooksFiltered($author, $genre)
   {
         $sql = "SELECT * from book_full_view 
            WHERE UPPER(categories) LIKE UPPER(:genre)
              AND UPPER(authors) LIKE UPPER(:author)
            ORDER BY title ASC";

        $stmt = oci_parse($this->conn, $sql);

        $author = '%' . $author . '%';
        $genre = '%' . $genre . '%';

        oci_bind_by_name($stmt, ':author', $author);
        oci_bind_by_name($stmt, ':genre', $genre);
        oci_execute($stmt);

        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (isset($row['DESCRIPTION']) && is_object($row['DESCRIPTION'])) {
                $row['DESCRIPTION'] = $row['DESCRIPTION']->load();
            }
            $books[] = $row;
        }

        return $books;
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

    public function updateBookStatus($userId, $bookId, $status) {
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
        // Get the total pages for the book
        $totalPagesSql = "SELECT pages FROM Book WHERE book_id = :book_id";
        $totalPagesStmt = oci_parse($this->conn, $totalPagesSql);
        oci_bind_by_name($totalPagesStmt, ':book_id', $bookId);
        oci_execute($totalPagesStmt);
        $bookData = oci_fetch_assoc($totalPagesStmt);
        $totalPages = $bookData ? intval($bookData['PAGES']) : 0;
        
        // Check if record exists
        $existingRecord = $this->getUserBookData($userId, $bookId);
        
        // Determine status based on pages read
        $newStatus = $existingRecord['STATUS'] ?? 'reading';
        if ($totalPages > 0 && intval($pagesRead) >= $totalPages) {
            $newStatus = 'completed';
            $pagesRead = $totalPages; // Ensure we don't exceed total pages
        } elseif (intval($pagesRead) > 0) {
            $newStatus = 'reading';
        }
        
        if ($existingRecord) {
            $sql = "UPDATE UserBook 
                    SET pages_read = :pages_read,
                        status = :status
                    WHERE user_id = :user_id 
                    AND book_id = :book_id";
        } else {
            $sql = "INSERT INTO UserBook (user_id, book_id, pages_read, status) 
                    VALUES (:user_id, :book_id, :pages_read, :status)";
        }
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':pages_read', $pagesRead);
        oci_bind_by_name($stmt, ':status', $newStatus);
        
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
        $status = 'to-read'; 
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
    public function findGoogleId($id)
    {
        $sql = "SELECT book_id FROM Book WHERE google_books_id = :google_id";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':google_id', $id);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row ? $row['BOOK_ID'] : null;
    }

    /// asta e folosit pentru admin page !!!
    public function getBooksBySource($source) {
        $sql = "SELECT b.book_id as BOOK_ID, b.title as TITLE, b.cover_url as COVER_URL, a.name as AUTHOR_NAME 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                WHERE b.source_api = :source";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':source', $source);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }
    public function getBooksByTitle($title) {
        $baseTitle = preg_replace('/(:\s.*)|(\s+\(.*\))|(,\s+Vol\.\s+\d+)|(,\s+Edition\s+\d+)/i', '', $title);
        $baseTitle = trim($baseTitle);
        $searchTitle = '%' . strtoupper($baseTitle) . '%';

        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                WHERE UPPER(b.title) LIKE :title
                ORDER BY b.publication_year DESC";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':title', $searchTitle);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getBooksByAuthor($authorName) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                WHERE UPPER(a.name) = UPPER(:author_name)";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':author_name', $authorName);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getBooksByPublisher($publisherName) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN PublishingHouse ph ON b.publishing_house_id = ph.publishing_house_id
                WHERE UPPER(ph.name) = UPPER(:publisher_name)";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':publisher_name', $publisherName);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getBooksByTranslator($translatorName) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN Translator t ON b.translator_id = t.translator_id
                WHERE UPPER(t.name) = UPPER(:translator_name)";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':translator_name', $translatorName);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getBooksBySubPublisher($subPublisherName) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                JOIN SubPublisher sp ON b.sub_publisher_id = sp.sub_publisher_id
                WHERE UPPER(sp.name) = UPPER(:sub_publisher_name)";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':sub_publisher_name', $subPublisherName);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getBooksByGenre($genre) {
        $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name 
                FROM Book b 
                JOIN Author a ON b.author_id = a.author_id
                WHERE UPPER(b.genre) LIKE UPPER(:genre)";
        
        $stmt = oci_parse($this->conn, $sql);
        $searchGenre = "%$genre%"; // Add wildcards to match partial genres
        oci_bind_by_name($stmt, ':genre', $searchGenre);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $books[] = $row;
        }
    
        return $books;
    }

    public function addReview($userId, $bookId, $stars,$reviewText)
    {
        $sql = "INSERT INTO review(user_id, book_id,text,stars)
                VALUES (:user_id, :book_id,:review_text,:stars)";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':stars', $stars);
        oci_bind_by_name($stmt, ':review_text', $reviewText);
        
        if (oci_execute($stmt)) {
            return true;
        } else {
            $e = oci_error($stmt);
            throw new \Exception("Error adding review: " . $e['message']);
        }
    }


    public function getReviewsByBookId($bookId,$userId) {
        $sql = "SELECT r.review_id, r.text, r.stars, u.username 
                FROM review r 
                JOIN users u ON r.user_id = u.user_id 
                WHERE r.book_id = :book_id AND r.user_id != :user_id
                ORDER BY r.review_id DESC";
        
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        
        $reviews = [];
        while ($row = oci_fetch_assoc($stmt)) {
             if (isset($row['TEXT']) && is_object($row['TEXT'])) {
            $row['TEXT'] = $row['TEXT']->read($row['TEXT']->size());
        }
            if (isset($row['USERNAME']) && is_object($row['USERNAME'])) {
                $row['USERNAME'] = $row['USERNAME']->read($row['USERNAME']->size());
            }
        $reviews[] = $row;
        }
        
        return $reviews;
    }

    public function getReviewsPerUser($userId,$bookId)
    {
          $sql = "SELECT r.review_id, r.text, r.stars, u.username 
                FROM review r 
                JOIN users u ON r.user_id = u.user_id 
                WHERE r.book_id = :book_id  and r.user_id = :user_id
                ORDER BY r.review_id DESC";
           
        $reviews = [];
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':book_id', $bookId);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
         while ($row = oci_fetch_assoc($stmt)) {
             if (isset($row['TEXT']) && is_object($row['TEXT'])) {
            $row['TEXT'] = $row['TEXT']->read($row['TEXT']->size());
        }
            if (isset($row['USERNAME']) && is_object($row['USERNAME'])) {
                $row['USERNAME'] = $row['USERNAME']->read($row['USERNAME']->size());
            }
            if (isset($row['REVIEW_ID']) && is_object($row['REVIEW_ID'])) {
                $row['REVIEW_ID'] = $row['REVIEW_ID']->read($row['REVIEW_ID']->size());
            }
        $reviews[] = $row;
        }
        return $reviews;


    }
    public function deleteReviewById($reviewId) {
        $sql = "DELETE FROM review WHERE review_id = :review_id";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':review_id', $reviewId);
        
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            throw new Exception("Deletion failed: " . $e['message']);
        }
        
        return true;
    }


    public function getBookTitleById($bookId) {
            $sql = "SELECT TITLE FROM BOOK WHERE BOOK_ID = :book_id";
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt, ":book_id", $bookId);
            oci_execute($stmt);
            $row = oci_fetch_assoc($stmt);
            return $row ? $row['TITLE'] : null;
    }

    public function getBooksByManualOrImport() {
        try {
            $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name
                    FROM Book b
                    LEFT JOIN Author a ON b.author_id = a.author_id
                    WHERE b.source_api IN ('MANUAL', 'JSON_IMPORT')
                    ORDER BY b.title";
                    
            $stmt = oci_parse($this->conn, $sql);
            oci_execute($stmt);
            
            $books = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $books[] = $row;
            }
            
            oci_free_statement($stmt);
            return $books;
        } catch (Exception $e) {
            error_log('Error in getBooksByManualOrImport: ' . $e->getMessage());
            return [];
        }
    }

    public function getUndiscoveredBooksByManualOrImport($userId) {
        try {
            $sql = "SELECT b.book_id, b.title, b.cover_url, a.name as author_name
                    FROM Book b
                    LEFT JOIN Author a ON b.author_id = a.author_id
                    WHERE b.source_api IN ('MANUAL', 'JSON_IMPORT')
                    AND NOT EXISTS (
                        SELECT 1 FROM UserBook ub 
                        WHERE ub.book_id = b.book_id 
                        AND ub.user_id = :user_id
                    )
                    ORDER BY b.title";
                    
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt, ':user_id', $userId);
            oci_execute($stmt);
            
            $books = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $books[] = $row;
            }
            
            oci_free_statement($stmt);
            return $books;
        } catch (Exception $e) {
            error_log('Error in getUndiscoveredBooksByManualOrImport: ' . $e->getMessage());
            return [];
        }
    }

    public function deleteBook($bookId) {
        $sql = "BEGIN delete_book_by_id(:book_id); END;";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':book_id', $bookId);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            throw new Exception("Deletion failed: " . $e['message']);
        }

        return true;
    }

    public function updateBook($bookId, $data) {
        try {
            $sql = "BEGIN update_book(
                    :book_id, :title, :author, :translator, :genre, :pages,
                    :pub_year, :lang, :isbn, :cover_url, :summary, :publisher, :sub_publisher
                    ); END;";

            $stmt = oci_parse($this->conn, $sql);

            oci_bind_by_name($stmt, ':book_id', $bookId);
            oci_bind_by_name($stmt, ':title', $data['title']);
            oci_bind_by_name($stmt, ':author', $data['author']);
            oci_bind_by_name($stmt, ':translator', $data['translator']);
            oci_bind_by_name($stmt, ':genre', $data['genre']);
            oci_bind_by_name($stmt, ':pages', $data['pages']);
            oci_bind_by_name($stmt, ':pub_year', $data['publication_year']);
            oci_bind_by_name($stmt, ':lang', $data['language']);
            oci_bind_by_name($stmt, ':isbn', $data['isbn']);
            oci_bind_by_name($stmt, ':cover_url', $data['cover_url']);
            
            // Special handling for CLOB data
            $clob = oci_new_descriptor($this->conn, OCI_D_LOB);
            oci_bind_by_name($stmt, ':summary', $clob, -1, OCI_B_CLOB);
            $clob->writeTemporary($data['summary'], OCI_TEMP_CLOB);
            
            oci_bind_by_name($stmt, ':publisher', $data['publishing_house']);
            oci_bind_by_name($stmt, ':sub_publisher', $data['sub_publisher']);

            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                throw new \Exception("Update failed: " . $e['message']);
            }
            
            $clob->free();
            return true;
        } catch (\Exception $e) {
            error_log("Error in updateBook: " . $e->getMessage());
            throw $e;
        }
    }
   
     public function searchBooks($query)
     {
        $sql = "SELECT  * FROM book_full_view
        WHERE (UPPER(title) LIKE UPPER(:query) 
        OR UPPER(categories) LIKE UPPER(:query) 
        OR UPPER(authors) LIKE UPPER(:query))
        ORDER BY title ASC";
        
        $stmt = oci_parse($this->conn, $sql);
        $searchQuery = '%' . $query . '%';
        oci_bind_by_name($stmt, ':query', $searchQuery);
        oci_execute($stmt);
        
        $books = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $row['DESCRIPTION'] = $row['DESCRIPTION']->load();
            $books[] = $row;
        }
        
        return $books;
     }
}