<?php 

namespace App\Models;

class UserModel{

    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn=$dbConnection;
    }

    public function createUser($email,$username,$hashedPassword)
    {   
        $sql="INSERT INTO users(username,email,password_hash) values
         (:username,:email,:password_hash)";

         $stmt=oci_parse($this->conn,$sql);
         
         oci_bind_by_name($stmt, ':username', $username);
         oci_bind_by_name($stmt, ':email', $email);
         oci_bind_by_name($stmt, ':password_hash', $hashedPassword);
 
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

}

