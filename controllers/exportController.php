<?php

namespace App\Controllers;

use App\Models\UserModel;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ExportController {
    
    private $conn;
    private $userId;
    
    public function __construct() {
      
        require_once __DIR__ . '/../models/dbConnection.php';
        $this->conn = \getConnection();
        
        if (isset($_COOKIE['jwt'])) {
            try {
                $decoded = JWT::decode($_COOKIE['jwt'], new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                $email = $decoded->data->email;
                $userModel = new UserModel($this->conn);
                $this->userId = $userModel->getUserIdByEmail($email);
            } catch (Exception $e) {
                $this->redirectToLogin();
            }
        } else {
            $this->redirectToLogin();
        }
    }
    
    private function redirectToLogin() {
        header('Location: /ShelfControl/login');
        exit;
    }
    
    public function exportStatsCSV() {
        // 1. Colecteaza statisticile utilizatorului
        $userModel = new UserModel($this->conn);
        $stats = $userModel->getUserStats($this->userId);
        
        // 2. Ia informatiile utilizatorului (username, etc)

        $decoded = JWT::decode($_COOKIE['jwt'], new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
        $email = $decoded->data->email;
        $userData = $userModel->getUserByEmail($email);
        
       // 3. Seteaza header-ele pentru download CSV

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="shelf_control_statistics.csv"');
        
        // 4. Creeaza fisierul CSV direct in output

        $output = fopen('php://output', 'w');
        
        // 5. Adauga header-ul tabelului

        fputcsv($output, ['Statistic', 'Value']);
        
        // 6. Adauga datele statistice rand cu rand
        fputcsv($output, ['Username', $userData['USERNAME']]);
        fputcsv($output, ['Books Read', $stats['books_read']]);
        fputcsv($output, ['Currently Reading', $stats['currently_reading']]);
        fputcsv($output, ['Want to Read', $stats['want_to_read']]);
        fputcsv($output, ['Total Books', $stats['books_read'] + $stats['currently_reading'] + $stats['want_to_read']]);
        fputcsv($output, ['Generated On', date('Y-m-d H:i:s')]);
        
        fclose($output);
        exit; // Opreste executia pentru a nu corupe fisierul
    }
    
    public function exportStatsDocBook() {
        // 1. Colecteaza aceleasi date ca pentru CSV
        $userModel = new UserModel($this->conn);
        $stats = $userModel->getUserStats($this->userId);
        
       
        $decoded = JWT::decode($_COOKIE['jwt'], new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
        $email = $decoded->data->email;
        $userData = $userModel->getUserByEmail($email);
        
        // 2. Seteaza header-ele pentru download XML

        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="shelf_control_statistics.xml"');
        
        // 3. Creeaza XML-ul DocBook manual ca string

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <!DOCTYPE article PUBLIC "-//OASIS//DTD DocBook XML V4.5//EN" "http://www.oasis-open.org/docbook/xml/4.5/docbookx.dtd">
                <article>
                <title>ShelfControl Statistics Report</title>
                <section>
                    <title>User Statistics</title>
                    <para>Username: ' . htmlspecialchars($userData['USERNAME']) . '</para>
                    <table>
                    <title>Reading Statistics</title>
                    <tgroup cols="2">
                        <thead>
                        <row>
                            <entry>Statistic</entry>
                            <entry>Value</entry>
                        </row>
                        </thead>
                        <tbody>
                        <row>
                            <entry>Books Read</entry>
                            <entry>' . $stats['books_read'] . '</entry>
                        </row>
                        <row>
                            <entry>Currently Reading</entry>
                            <entry>' . $stats['currently_reading'] . '</entry>
                        </row>
                        <row>
                            <entry>Want to Read</entry>
                            <entry>' . $stats['want_to_read'] . '</entry>
                        </row>
                        <row>
                            <entry>Total Books</entry>
                            <entry>' . ($stats['books_read'] + $stats['currently_reading'] + $stats['want_to_read']) . '</entry>
                        </row>
                        </tbody>
                    </tgroup>
                    </table>
                    <para>Report generated on ' . date('Y-m-d H:i:s') . '</para>
                </section>
                </article>';
        
        echo $xml;  // Outputeaza XML-ul direct
        exit; // Opreste executia pentru a nu corupe fisierul
    }
}