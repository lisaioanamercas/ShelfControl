<?php


namespace App\Controllers;

class RegisterController{
 
    public function register(){
        require __DIR__ . '/../views/register.php';
        require __DIR__ . '/../dbConnection.php';

        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
        }

        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        } 
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        }
        elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } 
        elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        }
       
        if (empty($error)) {
            $checkSql = "SELECT COUNT(*) AS count FROM users WHERE email = :email OR username = :username";
            $checkStmt = oci_parse($conn, $checkSql);

            oci_bind_by_name($checkStmt, ':email', $email);
            oci_bind_by_name($checkStmt, ':username', $username);

            oci_execute($checkStmt);
            $row = oci_fetch_assoc($checkStmt);

            if ($row['COUNT'] > 0) {
                $error = 'Email or username already exists.';
            }

            oci_free_statement($checkStmt);
        }

     
        
        if(empty($error)){

             $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
             $sql="INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

             $stmt = oci_parse($conn, $sql);

             oci_bind_by_name($stmt, ':username', $username);
             oci_bind_by_name($stmt, ':email', $email);
             oci_bind_by_name($stmt, ':password', $hashedpassword);

             $result = oci_execute($stmt);

             if ($result) {
                echo '<div style="color: green; text-align: center;">Registration successful! Welcome, ' . htmlspecialchars($username) . '!</div>';
            } else {
                $e = oci_error($stmt);
                $error = 'Database error: ' . htmlspecialchars($e['message']);
            }

            oci_free_statement($stmt);

            if (!empty($error)) {
                echo '<div style="color: red; text-align: center;">' . htmlspecialchars($error) . '</div>';
            }
        }
        
        if (empty($error)) {
            echo '<div style="color: green; text-align: center;">Registration successful! Welcome, ' . htmlspecialchars($username) . '!</div>';
        } else {
            echo '<div style="color: red; text-align: center;">' . htmlspecialchars($error) . '</div>';
        }
    }
}
