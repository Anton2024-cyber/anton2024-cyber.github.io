<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$secretKey = 'your_secret_key';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jwt = $_POST['jwt'];

    try {
        $decoded = JWT::decode($jwt, $secretKey, ['HS256']);
        $username = $decoded->username;

        // Здесь вы можете обновить данные пользователя в базе данных
        // Например, обновление имени, телефона и email
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];

        // Пример запроса на обновление данных
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, gender = ? WHERE username = ?");
        $stmt->execute([$name, $phone, $email, $gender, $username]);

        echo "Данные успешно обновлены!";
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage();
    }
}
?>