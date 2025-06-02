<?php 

namespace App\Models;
 
class UserModel{

    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn=$dbConnection;
    }

    public function createUser($email,$username,$hashedPassword,$city)
    {   
        $sql="INSERT INTO users(username,email,password_hash,city) values
         (:username,:email,:password_hash,:city)";

         $stmt=oci_parse($this->conn,$sql);
         
         oci_bind_by_name($stmt, ':username', $username);
         oci_bind_by_name($stmt, ':email', $email);
         oci_bind_by_name($stmt, ':password_hash', $hashedPassword);
          oci_bind_by_name($stmt, ':city', $city);
 
         return oci_execute($stmt);
     }

    public function verifyUser($username,$email)
    {
        $sql="SELECT COUNT(*) as count FROM users where username=:username OR email=:email";

        $stmt=oci_parse($this->conn,$sql);

        oci_bind_by_name($stmt,':username',$username);
        oci_bind_by_name($stmt,':email',$email);

        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        return $row['COUNT'] > 0;

    }

    public function loginUser($email, $password)
    {
        $sql = "SELECT password_hash FROM users WHERE email = :email";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':email', $email);

        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        if ($row && isset($row['PASSWORD_HASH'])) {
            return password_verify($password, $row['PASSWORD_HASH']);
        }
        return false;
    }
    
    public function getUserIdByEmail($email)
    {
        $sql = "SELECT user_id FROM users WHERE email = :email";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':email', $email);

        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        return $row ? $row['USER_ID'] : null;
    }
    public function getUserRoleByEmail($email)
    {
        $sql = "SELECT role FROM users WHERE email = :email";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':email', $email);

        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        return $row ? $row['ROLE'] : null;
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':email', $email);
        oci_execute($stmt);
        return oci_fetch_assoc($stmt);
    }

    public function getUserStats($userId)
    {
        // Books read (status = 'completed')
        $sqlRead = "SELECT COUNT(*) AS books_read FROM UserBook WHERE user_id = :user_id AND status = 'completed'";
        $stmtRead = oci_parse($this->conn, $sqlRead);
        oci_bind_by_name($stmtRead, ':user_id', $userId);
        oci_execute($stmtRead);
        $read = oci_fetch_assoc($stmtRead);

        // Currently reading (status = 'reading')
        $sqlCurrent = "SELECT COUNT(*) AS currently_reading FROM UserBook WHERE user_id = :user_id AND status = 'reading'";
        $stmtCurrent = oci_parse($this->conn, $sqlCurrent);
        oci_bind_by_name($stmtCurrent, ':user_id', $userId);
        oci_execute($stmtCurrent);
        $current = oci_fetch_assoc($stmtCurrent);

        // Want to read (status = 'to-read')
        $sqlWant = "SELECT COUNT(*) AS want_to_read FROM UserBook WHERE user_id = :user_id AND status = 'to-read'";
        $stmtWant = oci_parse($this->conn, $sqlWant);
        oci_bind_by_name($stmtWant, ':user_id', $userId);
        oci_execute($stmtWant);
        $want = oci_fetch_assoc($stmtWant);

        return [
            'books_read' => $read['BOOKS_READ'] ?? 0,
            'currently_reading' => $current['CURRENTLY_READING'] ?? 0,
            'want_to_read' => $want['WANT_TO_READ'] ?? 0
        ];
    }
    public function getUsernameById($userId)
    {
        $sql = "SELECT username FROM users WHERE user_id = :user_id";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row ? $row['USERNAME'] : null;
    }
    public function getCityByEmail($userEmail)
    {
        $sql = "SELECT city FROM users WHERE email = :email";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':email', $userEmail);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row ? $row['CITY'] : null;
    }


}

