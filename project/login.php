<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$host = 'localhost';
$db = 'u68669';
$user = 'u68669';
$pass = '5943600';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Получение пользователя из базы данных
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Генерация JWT
        $secretKey = 'your_secret_key'; // Секретный ключ для подписи JWT
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // 1 час
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'username' => $username
        ];

        $jwt = JWT::encode($payload, $secretKey);
        echo "Вы успешно вошли!<br>";
        echo "Ваш JWT: $jwt<br>";
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>