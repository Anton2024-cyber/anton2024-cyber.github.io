<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$secretKey = 'your_secret_key'; // Замените на свой секретный ключ

function generateJWT($username) {
    global $secretKey;
    $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60), // Токен будет действителен 1 час
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