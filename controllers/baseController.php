<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaseController {
    private $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['JWT_SECRET_KEY'];
    }

    
    public function generateJWT($userId, $username) {
        $payload = [
            'iss' => 'ShelfControl', 
            'aud' => 'ShelfControlUsers', 
            'iat' => time(),
            'exp' => time() + (60 * 60), 
            'data' => [
                'userId' => $userId,
                'username' => $username
            ]
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

  
    public function validateJWT($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return null; 
        }
    }
}