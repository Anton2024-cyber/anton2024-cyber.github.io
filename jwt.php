<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$secretKey = 's3cr3tK3y!@#1234567890'; 

function generateJWT($username) {
    global $secretKey;
    $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60), 
        'username' => $username
    ];
    return JWT::encode($payload, $secretKey);
}

function validateJWT($token) {
    global $secretKey;
    try {
        return JWT::decode($token, $secretKey, ['HS256']);
    } catch (Exception $e) {
        return null;
    }
}
?>
